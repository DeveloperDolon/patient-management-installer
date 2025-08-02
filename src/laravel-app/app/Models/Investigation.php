<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investigation extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['investigation', 'type'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::ulid();
        });
    }

}
