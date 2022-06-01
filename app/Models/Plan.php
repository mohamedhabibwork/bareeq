<?php

namespace App\Models;

use App\Casts\ImageCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'wishing_count',
        'status',
        'images',
    ];

    protected $casts = [
        'images' => ImageCast::class,
        'wishing_count' => 'integer',
        'price' => 'double',
        'status' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_plans')->withTimestamps();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(WorkerUser::class, 'plan_id');
    }
}
