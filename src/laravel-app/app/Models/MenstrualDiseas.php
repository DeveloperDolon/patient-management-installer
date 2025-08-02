<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenstrualDiseas extends Model
{
    protected $table = 'menstrual_diseases';
    protected $fillable = ['name'];
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::ulid();
        });
    }
}
