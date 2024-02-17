<?php

namespace App\Http\Controllers\Note;

use App\Events\DestroyNoteEvent;
use App\Events\StoreNoteEvent;
use App\Events\UpdateNoteEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Models\Note\Note;
use App\Transformers\Note\NoteTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Purify\Facades\Purify;

class NoteController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . NoteTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Note $note)
    {
        $params = $this->filter_transform($note->transformer);
        $data = $this->search($note->table, $params, 'user_id', $this->user()->id);

        return $this->showAll($data, $note->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Note $note)
    {
        $this->validate($request, [
            'title' => ['required', 'max:50'],
            'body' => ['max:1000'],
            'audio' => ['file', 'mimes:audio', 'mimetypes:audio/*', 'max:512000'],
            'tag_id' => ['exists:tags,id'],
        ]);

        throw_unless($request->body or $request->$note,
            new ReportError(Lang::get('Al menos un campo es requerido'), 422));

        DB::transaction(function () use ($request, $note) {
            $note->title = $request->title;
            $note->body = Purify::clean($request->body);
            $note->audio = $request->audio ?
            Storage::disk('notes')->put($this->user()->id, $request->audio) : null;
            $note->user_id = $this->user()->id;
            $note->tag_id = $request->tag_id;
            $note->save();

            StoreNoteEvent::dispatch($this->user()->id);
        });


        return $this->showOne($note, $note->transformer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        throw_unless($note->user_id == $this->user()->id,
            new ReportError(Lang::get('Usuario no autorizado'), 403));

        return $this->showOne($note, $note->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        throw_unless($note->user_id == $this->user()->id,
            new ReportError(Lang::get('Usuario no autorizado'), 403));

        $this->validate($request, [
            'title' => ['max:50'],
            'body' => ['max:1000'],
            // 'audio' => ['file', 'mimes:audio', 'mimetypes:audio/*', 'max:512000'],
            'tag_id' => ['exists:tags,id'],
        ]);

        DB::transaction(function () use ($request, $note) {
            $updated = false;

            if ($this->is_diferent($note->title, $request->title)) {
                $updated = true;
                $note->title = Purify::clean($request->title);
            }

            if ($this->is_diferent($note->body, $request->body)) {
                $updated = true;
                $note->body = Purify::clean($request->body);
            }

            if ($this->is_diferent($note->tag_id, $request->tag_id)) {
                $updated = true;
                $note->tag_id = $request->tag_id;
            }

            if ($this->is_diferent($note->audio, $request->audio)) {
                $updated = true;
                //Storage::disk('notes')->delete( $this->user()->id ."/".$request->banner);

                $note->audio = $request->audio ?
                Storage::disk('notes')->put($this->user()->id, $request->audio) : null;
            }

            if ($updated) {
                UpdateNoteEvent::dispatch($this->user()->id);
                $note->push();
            }
        });

        return $this->showOne($note, $note->transformer, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        throw_unless($note->user_id == $this->user()->id,
            new ReportError(Lang::get('Usuario no autorizado'), 403));

        $note->delete();

        DestroyNoteEvent::dispatch($this->user()->id);

        return $this->showOne($note);
    }
}
