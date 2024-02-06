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
            'body' => ['max:500'],
            'audio' => ['file', 'mimes:audio', 'mimetypes:audio/*', 'max:512000'],
        ]);

        throw_unless($request->body or $request->$note,
            new ReportError(Lang::get('Al menos un campo es requerido'), 422));

        DB::transaction(function () use ($request, $note) {
            $note->body = Purify::clean($request->body);
            $note->audio = $request->audio ?
            Storage::disk('notes')->put($this->user()->id, $request->audio) : null;
            $note->user_id = $this->user()->id;
            $note->save();
        });

        StoreNoteEvent::dispatch();

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

        return $this->showOne($note);
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
            'body' => ['max:500'],
            'audio' => ['file', 'mimes:audio', 'mimetypes:audio/*', 'max:512000'],
        ]);

        DB::transaction(function () use ($request, $note) {
            $updated = false;

            if ($this->is_diferent($note->body, $request->body)) {
                $updated = true;
                $note->body = Purify::clean($request->body);
            }

            if ($this->is_diferent($note->audio, $request->audio)) {
                $updated = true;
                //Storage::disk('notes')->delete( $this->user()->id ."/".$request->banner);

                $note->audio = $request->audio ?
                Storage::disk('notes')->put($this->user()->id, $request->audio) : null;
            }

            if ($updated) {
                UpdateNoteEvent::dispatch();
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

        DestroyNoteEvent::dispatch();

        return $this->showOne($note);
    }
}
