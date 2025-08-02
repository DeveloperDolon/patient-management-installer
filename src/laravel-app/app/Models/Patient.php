<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        "name",
        "age",
        "occupation",
        "gender",
        "phone",
        "address",
        "religion",
        "blood_group",
        "doctor_id",
        "date_of_birth",
        "profile_picture",
        "report_images",
    ];

    public function prescriptions() {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::ulid();
        });
    }
}
