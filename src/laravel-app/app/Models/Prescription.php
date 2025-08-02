<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'complaints',
        'present_illness',
        'history_of_medication',
        'history_of_concomitant_illness',
        'family_disease_history',
        'menstrual_history',
        'personal_history',
        'general_examinations',
        'systemic_examinations',
        'dermatological_examinations',
        'site_involvement',
        'investigations',
        'report_images',
        'medication_guidelines',
        'advices',
        'special_procedures',
        'vaccination_history',
        'obstetric_history',
        'operational_history',
        'past_illness'
    ];

    protected $casts = [
        'complaints' => 'array',
        'present_illness' => 'array',
        'history_of_medication' => 'array',
        'history_of_concomitant_illness' => 'array',
        'family_disease_history' => 'array',
        'menstrual_history' => 'array',
        'personal_history' => 'array',
        'general_examinations' => 'array',
        'systemic_examinations' => 'array',
        'dermatological_examinations' => 'array',
        'site_involvement' => 'array',
        'investigations' => 'array',
        'report_images' => 'array',
        'medication_guidelines' => 'array',
        'advices' => 'array',
        'special_procedures' => 'array',
        'vaccination_history' => 'array',
        'obstetric_history' => 'array',
        'operational_history' => 'array',
        'past_illness' => 'array'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::ulid();
        });
    }
}
