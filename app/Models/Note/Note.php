<?php

namespace App\Models\Note;

use App\Models\Master;
use App\Transformers\Note\NoteTransformer;

class Note extends Master
{
    public $table = "notes";

    public $view = "";

    public $transformer = NoteTransformer::class;

    protected $fillable = [
        'text',
        'body',
    ];

}
