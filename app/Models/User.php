<?php

namespace App\Models;

use App\Casts\HashCast;
use App\Casts\ImageCast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends BaseAuth
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'status',
        'password',
        'wish_day',
        'start_time',
        'end_time',
        'lat',
        'lng',
        'otp_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'phone_verified_at' => 'datetime',
        'password' => HashCast::class,
    ];

    public function wishDay(): Attribute
    {
        return Attribute::set(fn($value) => mb_strtolower($value));
    }

    /**
     * @return HasOne
     */
    public function car(): HasOne
    {
        return $this->hasOne(Car::class, 'user_id')->latestOfMany();
    }

    public function single_requests(): HasMany
    {
        return $this->hasMany(SingleRequest::class, 'user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function order_in_plan(): BelongsToMany
    {
        return $this->orders()->wherePivotNotNull('plan_id');
    }

    /**
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Worker::class, 'worker_users')
            ->withTimestamps()
            ->withPivot(['after_images','before_images', 'plan_id'])
            ->withCasts(['after_images' => ImageCast::class,'before_images' => ImageCast::class,]);
    }

    /**
     * @return Plan|null
     */
    public function selectPlan(): ?Plan
    {
        return $this->plans()
            ->withCount('orders')
            ->having('plans.wishing_count', '>', 'orders_count')->first();
    }

    /**
     * @return BelongsToMany
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'user_plans')->withTimestamps();
    }

    public function availableOrderInPlan(Plan $plan): bool
    {
        $this->loadCount(['orders' => fn($q) => $q->where('plan_id', $plan->id)]);
        return $this->orders_count < $plan->withCount;
    }

}
