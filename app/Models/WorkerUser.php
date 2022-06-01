<?php

namespace App\Models;

use App\Casts\ImageCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerUser extends BaseModel
{
    use HasFactory;

    public const ORDER_STATUS = [
        'pending' => 0,
        'success' => 1,
        'progress' => 2,
    ];
    public const USER_STATUS = [
        'pending' => 0,
        'success' => 1,
        'progress' => 2,
        'changed' => 3,
    ];
    public $incrementing = true;

    protected $table = 'worker_user';

    protected $fillable = ['worker_id', 'user_id', 'plan_id', 'after_images', 'before_images', 'order_status', 'user_status'];

    protected $casts = [
        'after_images' => ImageCast::class,
        'before_images' => ImageCast::class,
    ];

    protected $attributes = [
        'order_status' => self::ORDER_STATUS['pending'],
        'user_status' => self::USER_STATUS['pending'],
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    /**
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
