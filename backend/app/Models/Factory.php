<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Date;

    class Factory extends Model
    {
        /**
         * The attributes excluded from the model's JSON form.
         *
         * @var array
         */
        protected $hidden = ['id'];

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = ['hash', 'payload'];

        protected static function boot()
        {
            parent::boot();

            static::creating(function ($model) {
                $model->hash = sha1(config('app.name'.Date::today()->timestamp.Date::today()->microsecond));
            });
        }
    }
