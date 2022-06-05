<?php

namespace App\Models;

use App\Casts\HashCast;
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
        'city_id',
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
     * @return HasMany
     */
    public function order_in_plan(): HasMany
    {
        return $this->orders()->whereNotNull('plan_id')->where('worker_user.user_status', WorkerUser::USER_STATUS['success']);
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(WorkerUser::class);
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
        return $this->orders_count < $plan->wishing_count;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
