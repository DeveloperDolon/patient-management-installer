<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssociatedSite extends Model
{
    protected $table = 'associated_sites';

    protected $fillable = ['site'];
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
