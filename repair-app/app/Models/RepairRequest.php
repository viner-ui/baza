<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairRequest extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_DONE = 'done';
    public const STATUS_CANCELED = 'canceled';

    protected $table = 'repair_requests';

    protected $fillable = [
        'client_name',
        'phone',
        'address',
        'problem_text',
        'status',
        'assigned_to',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_ASSIGNED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_DONE,
            self::STATUS_CANCELED,
        ];
    }
}
