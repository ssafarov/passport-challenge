<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Date;

/**
 * Trait Uuid.
 */
trait Hasher
{
    /**
     * @param $query
     * @param $hash
     *
     * @return mixed
     */
    public function scopeHash($query, $hash)
    {
        return $query->where($this->getHashName(), $hash);
    }

    /**
     * @return string
     */
    public function getHashName()
    {
        return property_exists($this, 'hash') ? $this->hash : 'hash';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getHashName()} = sha1(config('app.name'.Date::today()->timestamp.Date::today()->microsecond));
        });
    }
}
