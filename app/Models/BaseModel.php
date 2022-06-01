<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public function getLang($name)
    {
        return $this->getAttribute($name)[current_local()] ?? '';
    }
}
