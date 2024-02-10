<?php

namespace App\Transformers\Note;

use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract
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
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($tag)
    {
        return [
            'id' => $tag->id,
            'tag' => $tag->name,
            'links' => [
                'parent' => route('tags.index'),
                'store' => route('tags.index'),
                'show' => route('tags.show', ['tag' => $tag->id]),
                'update' => route('tags.update', ['tag' => $tag->id]),
                'destroy' => route('tags.destroy', ['tag' => $tag->id]),
            ],
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'tag' => 'name',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'name' => 'tag',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'tag' => 'name',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
