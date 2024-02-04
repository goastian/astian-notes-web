<?php

namespace App\Transformers\Note;

use League\Fractal\TransformerAbstract;

class NoteTransformer extends TransformerAbstract
{
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
        return [
            'id' => $note->id,
            'cuerpo' => $note->body,
            'audio' => $note->audio,
            'creado' => $note->created_at,
            'actualizado' => $note->updated_at,
            'links' => [
                'parent' => route('notes.index'),
                'store' => route('notes.index'),
                'show' => route('notes.index', ['note' => $note->id]),
                'update' => route('notes.index', ['note' => $note->id]),
                'destroy' => route('notes.index', ['note' => $note->id]),
            ],
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'cuerpo' => 'body',
            'audio' => 'audio',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'body' => 'cuerpo',
            'audio' => 'audio',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'cuerpo' => 'body',
            'audio' => 'audio',
            'creado' => 'created_at',
            'updated_at' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
