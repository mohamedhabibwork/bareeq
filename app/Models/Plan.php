<?php

namespace App\Models;

use App\Casts\ImageCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Plan extends BaseModel
{
    use HasFactory;

    const TYPE = [
        'normal' => 0,
        'best' => 1,
    ];

    protected $fillable = [
        'name',
        'price',
        'description',
        'wishing_count',
        'status',
        'type',
        'images',
    ];

    protected $attributes = [
        'type' => self::TYPE['normal'],
    ];

    protected $casts = [
        'images' => ImageCast::class,
        'wishing_count' => 'integer',
        'price' => 'double',
        'status' => 'boolean',
        'type' => 'int',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_plans')->withTimestamps();
    }

    /**
     * @return MorphMany
     */
    public function orders(): MorphMany
    {
        return $this->morphMany(WorkerUser::class, 'plan');
    }

}
