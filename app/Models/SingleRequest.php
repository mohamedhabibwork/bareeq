<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SingleRequest extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'car_name',
        'car_type',
        'phone',
        'address',
        'car_area',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return MorphMany
     */
    public function orders(): MorphMany
    {
        return $this->morphMany(WorkerUser::class, 'plan');
    }
}
