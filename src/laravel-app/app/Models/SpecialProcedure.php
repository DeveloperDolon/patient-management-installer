<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialProcedure extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'special_procedures';
    protected $fillable = [
        'procedure'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::ulid();
        });
    }
}
