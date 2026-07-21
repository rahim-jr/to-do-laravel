<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_completed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the todo.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tags for the todo.
     *
     * @return BelongsToMany<tags, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(tags::class, 'tag_todo', 'todo_id', 'tag_id');
    }

    /**
     * Get the attachments for the todo.
     *
     * @return HasMany<attachments, $this>
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(attachments::class);
    }
}
