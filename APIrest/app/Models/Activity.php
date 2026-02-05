<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_DONE = 'done';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_IN_PROGRESS,
        self::STATUS_DONE,
    ];
    
    protected $fillable = [
        'title',
        'status',
        'date',
        'description',
        'user_id',
        'client_id',
        'description',
        'completed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo {

        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo {

        return $this->belongsTo(Client::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isDone(): bool
    {
        return $this->status === self::STATUS_DONE;
    }
    
}
