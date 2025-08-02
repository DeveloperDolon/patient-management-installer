@extends('dashboard-layout')

@section('content')
    <div class="mx-auto mt-10 max-w-5xl rounded-xl bg-white shadow-xl ring-1 ring-gray-200">
        {{-- Header with Profile --}}
        <div
            class="flex items-center justify-between gap-6 rounded-t-xl border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100 px-8 py-6"
        >
            <div class="flex items-center gap-6">
                <img
                    src="{{ asset($patient->profile_picture) }}"
                    alt="Profile Picture"
                    class="h-20 w-20 rounded-full border-2 border-blue-400 object-cover shadow-sm"
                />
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $patient->name }}</h2>
                    <p class="text-sm text-gray-600">Patient ID: {{ $patient->id }}</p>
                </div>
            </div>

            <a href="{{ route('dashboard.prescriptions.print', $prescription) }}">
                <button
                    class="cursor-pointer rounded border border-red-500 px-2 py-1 text-sm text-red-500 transition-colors hover:bg-red-500 hover:text-white md:text-base"
                >
                    <i class="fa-solid fa-print"></i>
                </button>
            </a>
        </div>

        {{-- Body --}}
        <div class="space-y-6 px-8 py-6 text-sm text-gray-800">
            {{-- Patient Details --}}
            <div class="grid grid-cols-1 gap-4 text-sm text-gray-700 md:grid-cols-2">
                <div>
                    <span class="font-semibold">Age:</span>
                    {{ $patient->age }}
                </div>
                <div>
                    <span class="font-semibold">Gender:</span>
                    {{ ucfirst($patient->gender) }}
                </div>
                <div>
                    <span class="font-semibold">Phone:</span>
                    {{ $patient->phone }}
                </div>
                <div>
                    <span class="font-semibold">Address:</span>
                    {{ $patient->address }}
                </div>
                <div>
                    <span class="font-semibold">Blood Group:</span>
                    {{ $patient->blood_group }}
                </div>
                <div>
                    <span class="font-semibold">Religion:</span>
                    {{ $patient->religion }}
                </div>
                <div>
                    <span class="font-semibold">Date of Birth:</span>
                    {{ $patient->date_of_birth }}
                </div>
            </div>

            {{-- Prescription Sections --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                @php
                    function displayCard($title, $items, $isFullCol = false)
                    {
                        $classes = 'rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md';
                        if ($isFullCol) {
                            $classes = 'col-span-2 ' . $classes;
                        }

                        if (! $items || ! is_array($items) || count($items) === 0) {
                            return;
                        }
                        echo "<div class='{$classes}'>";
                        echo "<h4 class='text-sm font-semibold text-blue-700 mb-2'>{$title}</h4>";
                        echo '<ul class="list-inside space-y-1 text-sm text-gray-700">';
                        foreach ($items as $item) {
                            echo "<li><i class='fa-solid fa-hand-point-right mr-1'></i>{$item}</li>";
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                @endphp

                {{ displayCard('Complaints', $prescription->complaints) }}
                {{ displayCard('Present Illness', $prescription->present_illness) }}
                {{ displayCard('Past Illness', $prescription->past_illness) }}
                {{ displayCard('History of Medication', $prescription->history_of_medication) }}
                {{ displayCard('History of Concomitant Illness', $prescription->history_of_concomitant_illness) }}
                {{ displayCard('Family Disease History', $prescription->family_disease_history) }}
                {{ displayCard('Menstrual History', $prescription->menstrual_history) }}
                {{ displayCard('Personal History', $prescription->personal_history) }}
                {{ displayCard('General Examinations', $prescription->general_examinations) }}
                {{ displayCard('Systemic Examinations', $prescription->systemic_examinations) }}
                {{ displayCard('Dermatological Examinations', $prescription->dermatological_examinations) }}
                {{ displayCard('Site Involvement', $prescription->site_involvement) }}
                {{ displayCard('Investigations', $prescription->investigations) }}
                {{ displayCard('Advices', $prescription->advices) }}
                {{ displayCard('Special Procedures', $prescription->special_procedures) }}
                {{ displayCard('Vaccination History', $prescription->vaccination_history) }}
                {{ displayCard('Obstetric History', $prescription->obstetric_history) }}
                {{ displayCard('Operational History', $prescription->operational_history) }}
                
                <div
                    class="col-span-2 rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md"
                >
                    <h4 class="mb-2 text-sm font-semibold text-blue-700">Medicine Guideline</h4>
                    <div class="mx-auto w-full">
                        <div class="overflow-hidden rounded-lg bg-white shadow-md">
                            @if (empty($prescription->medication_guidelines))
                                <div class="p-6 text-center text-gray-500">No medicines have been prescribed.</div>
                            @else
                                <table class="w-full text-left text-sm text-gray-600">
                                    <thead class="border-b bg-gray-100 text-xs text-gray-700 uppercase">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Medicine Name</th>
                                            <th scope="col" class="px-6 py-3">Dose</th>
                                            <th scope="col" class="px-6 py-3">Frequency</th>
                                            <th scope="col" class="px-6 py-3">Duration</th>
                                            <th scope="col" class="px-6 py-3">Instruction</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($prescription->medication_guidelines as $guideline)
                                            <tr class="border-b bg-white hover:bg-gray-50">
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 font-bold whitespace-nowrap text-gray-900"
                                                >
                                                    {{ $guideline['name'] }}
                                                </th>
                                                <td class="px-6 py-4">
                                                    {{ $guideline['dose'] }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <!-- Re-using the same frequency pill logic for a great visual -->
                                                    <div class="flex items-center space-x-2">
                                                        <span
                                                            class="{{ in_array('morning', $guideline['frequency']) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-400' }} rounded-full px-2.5 py-0.5 text-xs font-medium"
                                                        >
                                                            Morning
                                                        </span>
                                                        <span
                                                            class="{{ in_array('noon', $guideline['frequency']) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-400' }} rounded-full px-2.5 py-0.5 text-xs font-medium"
                                                        >
                                                            Noon
                                                        </span>
                                                        <span
                                                            class="{{ in_array('night', $guideline['frequency']) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-400' }} rounded-full px-2.5 py-0.5 text-xs font-medium"
                                                        >
                                                            Night
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">{{ $guideline['duration_days'] }} Days</td>
                                                <td class="px-6 py-4">
                                                    {{ $guideline['meal_instruction'] === 'after' ? 'After Meal' : 'Before Meal' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>

                <div
                    class="col-span-2 rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md"
                >
                    <label class="mb-2 text-sm font-semibold text-blue-700">Medical Reports</label>

                    <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-5">
                        @php
                            $reports = is_array($prescription->report_images)
                                ? $prescription->report_images
                                : json_decode($prescription->report_images ?? '[]', true);
                        @endphp

                        @forelse ($reports as $img)
                            <x-image
                                image="{{$img}}"
                                alt="Report image"
                                class="h-48 w-full rounded-md border object-cover"
                                deleteRoute="{{route('dashboard.prescriptions.image-delete', $prescription->id)}}"
                            />
                        @empty
                            <p class="col-span-2 text-gray-500">No report images available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between rounded-b-xl border-t bg-gray-50 px-8 py-4 text-xs text-gray-500">
            <span>Created at: {{ $prescription->created_at->format('Y-m-d H:i') }}</span>
            <a
                href="{{ route('dashboard.patients.prescriptions', $patient->id) }}"
                class="text-blue-600 hover:underline"
            >
                ← Back to Prescriptions
            </a>
        </div>
    </div>
@endsection
