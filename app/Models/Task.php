<?php

namespace App\Models;

use App\Builders\TaskBuilder;
use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'status', 'user_id', 'parent_id', 'due_date',
    ];

    protected $casts = [
        'status'   => TaskStatusEnum::class,
        'due_date' => 'datetime',

    ];

    public function newEloquentBuilder($query): TaskBuilder
    {
        return new TaskBuilder($query);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function canBeMarkedCompleted(): bool
    {
        return !$this->children()->where('status', '!=', TaskStatusEnum::Completed->value)->exists();

    }
}
