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
            'title' => $note->title,
            'body' => $note->body,
            'audio' => $note->audio,
            'tag_id' => $note->tag_id,
            'tag' => $tag ? $tag->name : null,
            'created' => $this->format_date($note->created_at),
            'updated' => $this->format_date($note->updated_at),
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
            'title' => 'title',
            'body' => 'body',
            'audio' => 'audio',
            'tag_id' => 'tag_id',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'title' => 'title',
            'body' => 'body',
            'audio' => 'audio',
            'tag_id' => 'tag_id',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'title' => 'title',
            'body' => 'body',
            'audio' => 'audio',
            'tag_id' => 'tag_id',
            'created' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
