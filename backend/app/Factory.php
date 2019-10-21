<?php

    namespace App\Models;

    use App\Models\Traits\Hasher;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Factory extends Model
    {
        use Hasher;

        /**
         * The attributes excluded from the model's JSON form.
         *
         * @var array
         */
        protected $hidden = [];

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'hash', 'title', 'amount', 'low', 'high'
        ];

        /**
         * Building tree structure
         *
         * @return BelongsTo
         */
        public function parent()
        {
            return $this->belongsTo(__CLASS__);
        }

        /**
         * Building tree structure
         *
         * @return HasMany
         */
        public function children()
        {
            return $this->hasMany(__CLASS__);
        }

    }
