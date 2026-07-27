<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class tags extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the todos for the tag.
     *
     * @return BelongsToMany<todo, $this>
     */
    public function todos(): BelongsToMany
    {
        return $this->belongsToMany(todo::class, 'tag_todo', 'tag_id', 'todo_id');
    }
}
