<?php

namespace App\Models;

use App\Casts\HashCast;
use App\Casts\ImageCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends BaseAuth
{
    use SoftDeletes, HasFactory;

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


    /**
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'worker_users')
            ->withTimestamps()
            ->withPivot(['after_images','before_images', 'plan_id'])
            ->withCasts(['after_images' => ImageCast::class,'before_images' => ImageCast::class,]);
    }
}
