<?php

namespace App\Http\Controllers\Note;

use App\Events\DestroyTagEvent;
use App\Events\UpdateTagEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Models\Note\Note;
use App\Models\Note\Tag;
use App\Transformers\Note\TagTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class TagController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . TagTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Tag $tag)
    {
        $params = $this->filter_transform($tag->transformer);

        $data = $this->search($tag->table, $params, 'user_id', $this->user()->id);

        return $this->showAll($data, $tag->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tag $tag)
    {
        $tags = Tag::where('user_id', $this->user()->id)->get();

        //se restringen a 4 categorias
        throw_if(count($tags) > 4 and !$this->userCan('notes_pro') and !$this->userCan('admin'),
            new ReportError(Lang::get("Acciones estan registringidas despues de " . count($tags) . " etiquetas"), 403));

        $this->validate($request, [
            'name' => ['required', Rule::notIn($tags->pluck('name'))],
        ]);

        DB::transaction(function () use ($request, $tag) {

            $tag = $tag->fill($request->only('name'));
            $tag->user_id = $this->user()->id;
            $tag->save();

            $this->privateChannel("StoreTagEvent", "New tag created", config('echo-client.channel') . "." . $this->user()->id);
        });

        return $this->showOne($tag, $tag->transformer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        throw_unless($tag->user_id == $this->user()->id,
            new ReportError(Lang::get('Usuario no autorizado'), 403));

        return $this->showOne($tag, $tag->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        throw_unless($tag->user_id == $this->user()->id,
            new ReportError(Lang::get('Usuario no autorizado'), 403));

        DB::transaction(function () use ($request, $tag) {

            if ($this->is_diferent($tag->name, $request->name)) {
                $tag->name = $request->name;
                $tag->push();

                $this->privateChannel("UpdateTagEvent", "Tag updated", config('echo-client.channel') . "." . $this->user()->id);
            }
        });

        return $this->showOne($tag, $tag->transformer, 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag, Note $note)
    {
        throw_unless($tag->user_id == $this->user()->id,
            new ReportError(Lang::get('Usuario no autorizado'), 403));

        DB::transaction(function () use ($tag, $note) {

            DB::table($note->table)->where('tag_id', $tag->id)->update([
                'tag_id' => null,
            ]);

            $tag->delete();

            $this->privateChannel("DestroyTagEvent","Tag deleted", config('echo-client.channel') . "." . $this->user()->id);
        });

        return $this->showOne($tag, $tag->transformer);
    }
}
