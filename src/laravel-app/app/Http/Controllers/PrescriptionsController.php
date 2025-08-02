<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Prescription;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class PrescriptionsController extends Controller
{
    public function create()
    {
        $id = request()->query('id');
        $patient = Patient::findOrFail($id);

        return view('dashboard.prescription-create', compact('patient'));
    }

    public function store(Request $request)
    {
        $validatedData = [];
        try {
            $validatedData = $request->validate([
                // --- Existing Fields ---
                'patient_id' => 'required|ulid|exists:patients,id',
                'complaints' => 'required|array',
                'present_illness' => 'required|array',
                'history_of_medication' => 'nullable|array',
                'history_of_concomitant_illness' => 'nullable|array',
                'family_disease_history' => 'nullable|array',
                'menstrual_history' => 'nullable|array',
                'personal_history' => 'nullable|array',
                'general_examinations' => 'nullable|array',
                'systemic_examinations' => 'nullable|array',
                'dermatological_examinations' => 'nullable|array',
                'site_involvement' => 'nullable|array',
                'investigations' => 'nullable|array',
                "report_images" => "nullable|array",
                "report_images.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
                // --- New Medicine Guideline Fields ---

                'medicine_name' => 'nullable|array',
                'medicine_name.*' => 'nullable|string|max:255',

                'dose_quantity' => 'nullable|array',
                'dose_quantity.*' => 'required_with:medicine_name.*|string|max:100',

                'duration' => 'nullable|array',
                'duration.*' => 'required_with:medicine_name.*|integer|min:1',

                'meal_instruction' => 'nullable|array',
                'meal_instruction.*' => 'required_with:medicine_name.*|string|in:before,after',

                'frequency' => 'nullable|array',
                'frequency.*' => 'required_with:medicine_name.*|array',
                'frequency.*.*' => 'required_with:medicine_name.*|string|in:morning,noon,night',

                'advices' => 'nullable|array',
                'advices.*' => 'nullable|string',

                'special_procedures' => 'nullable|array',
                'special_procedures.*' => 'nullable|string',

                'vaccination_history' => 'nullable|array',
                'vaccination_history.*' => 'nullable|string',

                'obstetric_history' => 'nullable|array',
                'obstetric_history.*' => 'nullable|string',

                'operational_history' => 'nullable|array',
                'operational_history.*' => 'nullable|string',

                'past_illness' => 'nullable|array',
                'past_illness.*' => 'nullable|string',
            ]);
        } catch (Exception $e) {
            return back()->with("error", $e->getMessage());
        }

        foreach ([
            'history_of_medication',
            'history_of_concomitant_illness',
            'general_examinations',
            'systemic_examinations',
            'dermatological_examinations',
            'investigations',
        ] as $field) {
            if (!isset($validatedData[$field])) {
                $validatedData[$field] = [];
            }
        }

        if ($request->hasFile('report_images')) {
            $reportPaths = [];
            foreach ($request->file('report_images') as $image) {
                $path = $image->store('report_images', 'public');
                $reportPaths[] = '/storage/' . $path;
            }

            $validatedData['report_images'] = json_encode($reportPaths);
        }

        $validatedData['doctor_id'] = Auth::user()->id;

        $guidelineData = [];

        if (!empty($validatedData['medicine_name'])) {
            foreach ($validatedData['medicine_name'] as $index => $name) {
                $guidelineData[] = [
                    'name' => $name,
                    'dose' => $validatedData['dose_quantity'][$index],
                    'frequency' => $validatedData['frequency'][$index],
                    'meal_instruction' => $validatedData['meal_instruction'][$index],
                    'duration_days' => $validatedData['duration'][$index],
                ];
            }
        }

        $dbData = $validatedData;

        $dbData['medication_guidelines'] = $guidelineData;

        unset(
            $dbData['medicine_name'],
            $dbData['dose_quantity'],
            $dbData['frequency'],
            $dbData['meal_instruction'],
            $dbData['duration']
        );

        Prescription::create($dbData);

        return back()->with('success', 'Prescription added successfully.');
    }

    public function show($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patient = $prescription->patient;

        return view('dashboard.prescription-show', compact('prescription', 'patient'));
    }

    public function edit($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patient = $prescription->patient;

        return view('dashboard.prescription-edit', compact('prescription', 'patient'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            // --- Existing Fields ---
            'patient_id' => 'required|ulid|exists:patients,id',
            'complaints' => 'required|array',
            'present_illness' => 'required|array',
            'history_of_medication' => 'nullable|array',
            'history_of_concomitant_illness' => 'nullable|array',
            'family_disease_history' => 'nullable|array',
            'menstrual_history' => 'nullable|array',
            'personal_history' => 'nullable|array',
            'general_examinations' => 'nullable|array',
            'systemic_examinations' => 'nullable|array',
            'dermatological_examinations' => 'nullable|array',
            'site_involvement' => 'nullable|array',
            'investigations' => 'nullable|array',
            "report_images" => "nullable|array",
            "report_images.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
            // --- New Medicine Guideline Fields ---

            'medicine_name' => 'nullable|array',
            'medicine_name.*' => 'nullable|string|max:255',

            'special_procedures' => 'nullable|array',
            'special_procedures.*' => 'nullable|string',

            'dose_quantity' => 'nullable|array',
            'dose_quantity.*' => 'required_with:medicine_name.*|string|max:100',

            'duration' => 'nullable|array',
            'duration.*' => 'required_with:medicine_name.*|integer|min:1',

            'meal_instruction' => 'nullable|array',
            'meal_instruction.*' => 'required_with:medicine_name.*|string|in:before,after',

            'advices' => 'nullable|array',
            'advices.*' => 'nullable|string',

            'frequency' => 'nullable|array',
            'frequency.*' => 'required_with:medicine_name.*|array',
            'frequency.*.*' => 'required_with:medicine_name.*|string|in:morning,noon,night',

            'vaccination_history' => 'nullable|array',
            'vaccination_history.*' => 'nullable|string',

            'obstetric_history' => 'nullable|array',
            'obstetric_history.*' => 'nullable|string',

            'operational_history' => 'nullable|array',
            'operational_history.*' => 'nullable|string',

            'past_illness' => 'nullable|array',
            'past_illness.*' => 'nullable|string',
        ]);

        foreach ([
            'history_of_medication',
            'history_of_concomitant_illness',
            'general_examinations',
            'systemic_examinations',
            'dermatological_examinations',
            'investigations',
        ] as $field) {
            if (!isset($validatedData[$field])) {
                $validatedData[$field] = [];
            }
        }

        $guidelineData = [];
        $prescription = Prescription::findOrFail($id);

        if ($request->hasFile('report_images')) {
            $reportPaths = [];
            foreach ($request->file('report_images') as $image) {
                $path = $image->store('report_images', 'public');
                $reportPaths[] = '/storage/' . $path;
            }

            if ($prescription->report_images) {
                $previousImages = json_decode($prescription->report_images, true) ?? [];
                $reportPaths = array_merge($previousImages, $reportPaths);
            }

            $validatedData['report_images'] = json_encode($reportPaths);
        }

        if (!empty($validatedData['medicine_name'])) {
            foreach ($validatedData['medicine_name'] as $index => $name) {
                $guidelineData[] = [
                    'name' => $name,
                    'dose' => $validatedData['dose_quantity'][$index],
                    'frequency' => $validatedData['frequency'][$index],
                    'meal_instruction' => $validatedData['meal_instruction'][$index],
                    'duration_days' => $validatedData['duration'][$index],
                ];
            }
        }

        $dbData = $validatedData;
        $dbData['medication_guidelines'] = $guidelineData;

        unset(
            $dbData['medicine_name'],
            $dbData['dose_quantity'],
            $dbData['frequency'],
            $dbData['meal_instruction'],
            $dbData['duration']
        );

        $prescription->update($dbData);

        return redirect()->back()->with('success', 'Prescription update successfully.');
    }

    public function delete($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->delete();

        return redirect()->back()->with('success', 'Prescription deleted successfully.');
    }

    public function deleteImage(Request $request, $id)
    {
        $prescription = Prescription::findOrFail($id);
        $images = json_decode($prescription->report_images, true) ?? [];
        $imageToDelete = $request->input('image');

        $relativePath = ltrim(str_replace('/storage/', '', $imageToDelete), '/');

        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }

        $filteredImages = array_filter($images, function ($img) use ($imageToDelete) {
            return $img !== $imageToDelete;
        });

        $prescription->report_images = json_encode(array_values($filteredImages));
        $prescription->save();

        return redirect()->back()->with('success', 'Report image deleted successfully.');
    }

    public function print(Prescription $prescription)
    {
        $data = [
            'prescription' => $prescription,
            'patient' => $prescription->patient,
            'doctor' => $prescription->doctor
        ];
        $clinic = Clinic::where('doctor_id', Auth::user()->id)
            ->where('is_active', true)
            ->first();

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $path = storage_path() . "/fonts";

        $html = view('print.prescription-print', $data)->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4', // 210mm width x 250mm height (custom A4 height)
            'margin_top' => 0,
            'margin_right' => 0,
            'padding_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_footer' => 0,
            'orientation' => 'P',
            'fontDir' => array_merge($fontDirs, [$path]),
            'fontdata' => $fontData + [
                'solaimanlipi' => [  // Changed key to lowercase
                    'R' => 'SolaimanLipi_Regular.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
                'balooda2' => [  // Changed key to lowercase
                    'R' => 'BalooDa2-Regular.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'solaimanlipi',
            'autoPageBreak' => true,
        ]);
        $mpdf->showImageErrors = true;
        $mpdf->debug = true;
        $mpdf->setAutoBottomMargin = 'stretch';

        $logoHtml = [];

        if (isset($clinic->logo) && $clinic->logo) {
            $logoPath = (strpos($clinic->logo, 'http') === 0)
                ? $_SERVER['DOCUMENT_ROOT'] . parse_url($clinic->logo, PHP_URL_PATH)
                : $clinic->logo;

            $relativePath = str_replace('/storage/', '', $logoPath);
            $fullPath = Storage::disk('public')->path($relativePath);

            if (!Storage::disk('public')->exists($relativePath)) {
                throw new \Exception("Image not found in storage: " . $relativePath);
            }

            // Convert to base64
            $imageData = base64_encode(file_get_contents($fullPath));
            $imageType = pathinfo($fullPath, PATHINFO_EXTENSION);
            $logoHtml = [
                'imageData' => $imageData,
                'imageType' => $imageType,
            ];
        }

        $mpdf->SetHTMLFooter(
            '<div class="footer-container">
                <div class="prescription-footer bn_font">
                <table style="margin: 0 auto;">
                    <tr>
                        <td style="padding-right: 10px; vertical-align: top;">
                            <img class="logo-image" src="data:image/' . ($logoHtml['imageType'] ?? '') . ';base64,' . ($logoHtml['imageData'] ?? '') . '" style="height: 70px; margin-left: 10px; width: 60px; border-radius: 40px; margin: 0 auto;">
                        </td>
                        <td style="vertical-align: top;">
                            <div class="footer-header">
                                <div class="clinic-name">
                                    <span style="margin-bottom: 20px; display: block;">' . (isset($clinic->clinic_name) && $clinic->clinic_name ? htmlspecialchars($clinic->clinic_name) : '') . '</span>
                                </div>
                            </div>
                            <div class="footer-content">
                                <div class="contact-info">
                                    <div class="info-row">
                                        <span class="info-label">' . (isset($clinic->clinic_address) && $clinic->clinic_address ? htmlspecialchars($clinic->clinic_address) : '') . '</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">' . (isset($clinic->clinic_phone) && $clinic->clinic_phone ? htmlspecialchars($clinic->clinic_phone) : '') . ' | ' . (isset($clinic->clinic_email) && $clinic->clinic_email ? htmlspecialchars($clinic->clinic_email) : '') . ' </span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">
                                            ' . (isset($clinic->visit_time) && $clinic->visit_time ? htmlspecialchars($clinic->visit_time) : '') . '
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
                </div>
            </div>'
        );

        $mpdf->WriteHTML($html);

        return response($mpdf->Output($data['patient']['name'] . '_Prescription.pdf', 'I'))->header('Content-Type', 'application/pdf');
    }
}
