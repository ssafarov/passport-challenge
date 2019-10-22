<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Date;

    class Tree extends Model
    {
        //
        protected $table = 'trees';

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
        protected $fillable = ['hash', 'data'];

        protected static function boot()
        {
            parent::boot();

            static::creating(function ($model) {
                if (!$model->hash) {
                    $model->hash = sha1(config('app.name'.Date::today()->timestamp.Date::today()->microsecond));
                }
            });
        }
    }
