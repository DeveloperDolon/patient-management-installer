<?php

namespace App\Http\Controllers;

use App\Models\ConcomitantDiseases;
use App\Models\Examination;
use App\Models\IllnessModel;
use App\Models\Investigation;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatisticsController extends Controller
{
    public function index()
    {
        $dateThreshold = now()->subDays(30);

        $patientsCount = Cache::remember('patient_counts', 60, function () use ($dateThreshold) {
            return Patient::selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as new,
                SUM(CASE WHEN created_at < ? THEN 1 ELSE 0 END) as old,
                AVG(DATEDIFF(CURDATE(), created_at)) as avg_patients
            ', [$dateThreshold, $dateThreshold])->first();
        });

        $examinationsCount = Examination::count();
        $investigationsCount = Investigation::count();
        $medicinesCount = Medicine::count();

        $latestPatients = Patient::orderBy('created_at', 'desc')
            ->take(3)
            ->select(['id', 'name', 'age', 'gender', 'profile_picture'])
            ->get();

        $prescriptionsCount = Prescription::count();

        // Get patient counts for the last 7 months
        $patientData = Cache::remember('patient_data_last_7_months', 60, function () {
            $months = [];
            $now = now();
            
            for ($i = 6; $i >= 0; $i--) {
                $month = $now->copy()->subMonths($i);
                $months[$month->format('Y-m')] = [
                    'label' => $month->format('M'),
                    'value' => 0,
                ];
            }

            $patientsByMonth = Patient::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
                ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            foreach ($patientsByMonth as $data) {
                if (isset($months[$data->month])) {
                    $months[$data->month]['value'] = $data->count;
                }
            }

            return array_values($months);
        });

        return view(
            'dashboard.statistics',
            compact([
                'patientsCount',
                'examinationsCount',
                'investigationsCount',
                'medicinesCount',
                'latestPatients',
                'prescriptionsCount',
                'patientData'
            ])
        );
    }
}
