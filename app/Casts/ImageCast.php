<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class ImageCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array|string
     */
    public function get($model, $key, $value, $attributes)
    {
        // check is json and parse
        if (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '[')))
            $value = json_decode($value, true);
        // check is string
        if (!is_array($value)) return check_image_exists_or_default(!empty($value ?? '') ? url($value) : $value);
        // here is arraying
        $images = [];
        foreach ($value as $key => $image) {
            $url = check_image_exists_or_default(!empty($image ?? '') ? url($image) : $image);
            if (is_string($key)) {
                $images[$key] = $url;
            } else {
                $images[] = $url;
            }
        }
        return $images;

    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string|array
     */
    public function set($model, $key, $value, $attributes)
    {
        if (is_array($value)) {
            $images = [];
            foreach ($value as $key => $image) {
                if (!$image instanceof UploadedFile) {
                    $images[$key] = $image;
                    continue;
                }
                $uploaded = uploader($image);
                if (is_string($key)) {
                    $images[$key] = $uploaded;
                } else {
                    $images[] = $uploaded;
                }
            }
            return json_encode($images, JSON_UNESCAPED_UNICODE);
        }
        return $value instanceof UploadedFile ? uploader($value) : $value;
    }
}
