<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class attachments extends Model
{
    use HasFactory;

    protected $fillable = [
        'todo_id',
        'file_path',
        'original_name',
    ];

    /**
     * Get the todo that owns the attachment.
     *
     * @return BelongsTo<todo, $this>
     */
    public function todo(): BelongsTo
    {
        return $this->belongsTo(todo::class);
    }
}
