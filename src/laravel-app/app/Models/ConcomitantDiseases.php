<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConcomitantDiseases extends Model
{
    protected $table = 'concomitant_diseases';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'category'];
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
