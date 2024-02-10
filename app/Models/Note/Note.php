<?php

namespace App\Models\Note;

use App\Models\Master;
use App\Models\Note\Tag;
use App\Transformers\Note\NoteTransformer;

class Note extends Master
{
    public $table = "notes";

    public $view = "";

    public $transformer = NoteTransformer::class;

    protected $fillable = [
        'title',
        'text',
        'body',
        'tag_id',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
