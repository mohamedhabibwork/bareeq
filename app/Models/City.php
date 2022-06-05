<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['name','locations'];

    protected static function booted()
    {
        parent::booted(); // TODO: Change the autogenerated stub
        static::creating(function (self $city) {
            if ($city->locations && !str_starts_with('ST_GeomFromText',$city->locations)) {
                $city->locations = "ST_GeomFromText('{$city->locations}',0)";
            }
        });
        static::updating(function (self $city) {
            if ($city->locations && !str_starts_with('ST_GeomFromText',$city->locations)) {
                $city->locations = "ST_GeomFromText('{$city->locations}',0)";
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'city_id');
    }
}
