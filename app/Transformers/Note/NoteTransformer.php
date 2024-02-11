<?php

namespace App\Transformers\Note;

use App\Models\Note\Tag;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class NoteTransformer extends TransformerAbstract
{

    use Asset;

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($note)
    {
        $tag = Tag::find($note->tag_id);

        return [
            'id' => $note->id,
            'titulo' => $note->title,
            'cuerpo' => $note->body,
            'audio' => $note->audio,
            'etiqueta_id' => $note->tag_id,
            'etiqueta' => $tag ? $tag->name : null,
            'creado' => $this->format_date($note->created_at),
            'actualizado' => $this->format_date($note->updated_at),
            'links' => [
                'parent' => route('notes.index'),
                'store' => route('notes.store'),
                'show' => route('notes.show', ['note' => $note->id]),
                'update' => route('notes.update', ['note' => $note->id]),
                'destroy' => route('notes.destroy', ['note' => $note->id]),
            ],
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'titulo' => 'title',
            'cuerpo' => 'body',
            'audio' => 'audio',
            'etiqueta_id' => 'tag_id',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'title' => 'titulo',
            'body' => 'cuerpo',
            'audio' => 'audio',
            'tag_id' => 'etiqueta_id',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'titulo' => 'title',
            'cuerpo' => 'body',
            'audio' => 'audio',
            'etiqueta_id' => 'tag_id',
            'creado' => 'created_at',
            'updated_at' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
