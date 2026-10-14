<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'customer_id',
        'content',
        'due_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'status'   => TaskStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** 期限切れ（未完了 かつ 期限が今日より前） */
    public function isOverdue(): bool
    {
        return $this->due_date !== null
            && $this->status !== TaskStatus::Done
            && $this->due_date->lt(today());
    }
}