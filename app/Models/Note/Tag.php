<?php

namespace App\Models\Note;

use App\Models\Master;
use App\Models\Note\Note;
use App\Transformers\Note\TagTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Master
{
    use HasFactory;

    public $table = "tags";

    public $view = "";

    public $timestamps = false;

    public $transformer = TagTransformer::class;

    protected $fillable = [
        'name',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
