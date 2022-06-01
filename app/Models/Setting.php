<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class Setting extends BaseModel
{
    protected $fillable = ['name', 'type', 'value', 'locale', 'group_by'];

    public static function validateCreate(array $validate = null): array
    {
        return $validate ?? [
                'name' => ['required', 'max:255'],
                'value' => ['required'],
                'locale' => ['required', Rule::in(locals())],
                'type' => ['required', Rule::in(['string', 'text', 'number', 'file'])],
            ];
    }

    public static function validateUpdate(array $validate = null): array
    {
        return $validate ?? [
                'name' => ['sometimes:max:255'],
                'value' => ['sometimes'],
                'locale' => ['sometimes', Rule::in(locals())],
                'type' => ['sometimes', Rule::in(['string', 'text', 'number', 'file'])],
            ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->locale ??= app()->getLocale();
            $model->type ??= 'string';
        });
    }

    public function scopeSearch(Builder $query, $column, $like = false)
    {
        $value = request($column, null);
        return $query->when($value, function (Builder $builder) use ($value, $like, $column) {
            $mark = $like ? '%' : '';
            return $builder->where($column, $like ? 'LIKE' : '=', $mark . $value . $mark);
        });
    }

    public function getValueAttribute($value)
    {
        return $this->type == 'file' ? asset($value) : $value;
    }
}
