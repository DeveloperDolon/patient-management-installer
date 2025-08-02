@extends('dashboard-layout')

@section('content')
    <div class="mx-auto mt-10 max-w-4xl rounded-lg bg-white p-8 shadow-lg">
        <div class="flex flex-wrap justify-between">
            <h2 class="mb-8 text-3xl font-semibold text-gray-800">Edit Prescription - {{ $patient->name }}</h2>

            <a
                href="{{ route('dashboard.patients.prescriptions', ['id' => $patient->id]) }}"
                class="inline-flex items-center rounded bg-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-300"
            >
                ← Back
            </a>
        </div>

        <form
            action="{{ route('dashboard.prescriptions.update', $prescription->id) }}"
            method="POST"
            class="space-y-6"
            enctype="multipart/form-data"
        >
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}" />

            <x-multiselect
                label="Complaints"
                input-name="complaints[]"
                :options="\App\Models\Complaint::all(['complaint'])"
                option-key="complaint"
                :selected="$prescription->complaints"
            />

            <x-multiselect
                label="Present Illness"
                input-name="present_illness[]"
                :options="\App\Models\IllnessModel::all(['illness'])"
                option-key="illness"
                :selected="$prescription->present_illness"
            />

            <x-multiselect
                label="Past Illness"
                input-name="past_illness[]"
                :options="\App\Models\IllnessModel::all(['illness'])"
                option-key="illness"
                :selected="$prescription->past_illness"
            />

            <x-multiselect
                label="History of Medication"
                input-name="history_of_medication[]"
                :options="\App\Models\Medicine::all(['name'])"
                option-key="name"
                :selected="$prescription->history_of_medication"
            />

            <x-multiselect
                label="Investigations"
                input-name="investigations[]"
                :options="\App\Models\Investigation::all(['investigation'])"
                option-key="investigation"
                :selected="$prescription->investigations"
            />

            <x-multiselect
                label="History of Concomitant Illness"
                input-name="history_of_concomitant_illness[]"
                :options="\App\Models\ConcomitantDiseases::all(['name'])"
                option-key="name"
                :selected="$prescription->history_of_concomitant_illness"
            />

            <x-multiselect
                label="General examinations"
                input-name="general_examinations[]"
                :options="\App\Models\Examination::where('type', 'general')->get()"
                option-key="examination"
                :selected="$prescription->general_examinations"
            />

            <x-multiselect
                label="Dermatological examinations"
                input-name="dermatological_examinations[]"
                :options="\App\Models\Examination::where('type', 'dermatological')->get()"
                option-key="examination"
                :selected="$prescription->dermatological_examinations"
            />

            <x-multiselect
                label="Systemic examinations"
                input-name="systemic_examinations[]"
                :options="\App\Models\Examination::where('type', 'systemic')->get()"
                option-key="examination"
                :selected="$prescription->systemic_examinations"
            />

            <x-multiselect
                label="Menstrual History"
                input-name="menstrual_history[]"
                :options="\App\Models\MenstrualDiseas::all(['name'])"
                option-key="name"
                :selected="$prescription->menstrual_history"
            />

            <x-multiselect
                label="Personal History"
                input-name="personal_history[]"
                :options="\App\Models\PersonalInfo::all(['name'])"
                option-key="name"
                :selected="$prescription->personal_history"
            />

            <x-multiselect
                label="Site Involvement"
                input-name="site_involvement[]"
                :options="\App\Models\AssociatedSite::all(['site'])"
                option-key="site"
                :selected="$prescription->site_involvement"
            />

            <x-multiselect
                label="Family Disease History"
                input-name="family_disease_history[]"
                :options="\App\Models\ConcomitantDiseases::all(['name'])"
                option-key="name"
                :selected="$prescription->family_disease_history"
            />

            <x-multiselect
                label="Special Procedures"
                input-name="special_procedures[]"
                :options="\App\Models\SpecialProcedure::all(['procedure'])"
                option-key="procedure"
                :selected="$prescription->special_procedures"
            />

            <x-multiselect
                label="Vaccination History"
                input-name="vaccination_history[]"
                :options="\App\Models\Vaccination::all(['name'])"
                option-key="name"
                :selected="$prescription->vaccination_history"
            />

            <x-multiselect
                label="Obstetric History"
                input-name="obstetric_history[]"
                :options="\App\Models\Obstetric::all(['name'])"
                option-key="name"
                :selected="$prescription->obstetric_history"
            />

             <x-multiselect
                label="Operational History"
                input-name="operational_history[]"
                :options="\App\Models\Operation::all(['name'])"
                option-key="name"
                :selected="$prescription->operational_history"
            />

            <div id="medicine-fields-container" class="space-y-6">
                {{-- medicine dose guideline section --}}
                @if ($prescription->medication_guidelines != null)
                    @foreach ($prescription->medication_guidelines as $index => $guideline)
                        <!-- Initial Medicine Entry (The first one is always visible) -->
                        <div class="medicine-entry relative rounded-xl border border-gray-200 bg-white p-6 shadow-lg">
                            <button
                                type="button"
                                class="remove-medicine-btn absolute top-4 right-4 text-gray-400 transition-colors hover:text-red-500"
                            >
                                <svg
                                    class="h-6 w-6"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </button>

                            <!-- This entry does not have a remove button -->
                            <h3 class="mb-4 text-lg font-semibold text-indigo-600">Medicine {{ $index + 1 }}</h3>
                            <div class="space-y-6">
                                <!-- Medicine Name -->
                                <div>
                                    <label for="medicine-name-0" class="block text-sm font-medium text-gray-700">
                                        Medicine Name
                                    </label>
                                    <input
                                        type="text"
                                        name="medicine_name[]"
                                        id="medicine-name-0"
                                        value="{{ $guideline['name'] }}"
                                        placeholder="e.g., Napa Extend"
                                        class="mt-1 block w-full rounded-md border-gray-300 p-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                </div>
                                <!-- Dosage and Frequency -->
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                    <div class="md:col-span-1">
                                        <label for="dose-quantity-0" class="block text-sm font-medium text-gray-700">
                                            Dose
                                        </label>
                                        <input
                                            type="text"
                                            name="dose_quantity[]"
                                            id="dose-quantity-0"
                                            value="{{ $guideline['dose'] }}"
                                            value="১ টা করে"
                                            class="mt-1 block w-full rounded-md border-gray-300 p-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Frequency</label>
                                        <div class="mt-2 flex items-center space-x-4 sm:space-x-6">
                                            <div class="flex items-center">
                                                <input
                                                    id="morning-{{ $index }}"
                                                    name="frequency[{{ $index }}][]"
                                                    value="morning"
                                                    type="checkbox"
                                                    {{ in_array('morning', $guideline['frequency'] ?? []) ? 'checked' : '' }}
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                />
                                                <label for="morning-0" class="ml-2 block text-sm text-gray-900">
                                                    Morning
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input
                                                    id="noon-{{ $index }}"
                                                    name="frequency[{{ $index }}][]"
                                                    value="noon"
                                                    type="checkbox"
                                                    {{ in_array('noon', $guideline['frequency'] ?? []) ? 'checked' : '' }}
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                />
                                                <label for="noon-0" class="ml-2 block text-sm text-gray-900">
                                                    Noon
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input
                                                    id="night-{{ $index }}"
                                                    name="frequency[{{ $index }}][]"
                                                    value="night"
                                                    type="checkbox"
                                                    {{ in_array('night', $guideline['frequency'] ?? []) ? 'checked' : '' }}
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                />
                                                <label for="night-0" class="ml-2 block text-sm text-gray-900">
                                                    Night
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Meal Instruction & Duration -->
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Meal Instruction</label>
                                        <div class="mt-2 flex items-center space-x-6">
                                            <div class="flex items-center">
                                                <input
                                                    id="before-meal-{{ $index }}"
                                                    name="meal_instruction[{{ $index }}]"
                                                    value="before"
                                                    {{ old("meal_instruction.$index", $guideline['meal_instruction']) == 'before' ? 'checked' : '' }}
                                                    type="radio"
                                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                />
                                                <label for="before-meal-0" class="ml-2 block text-sm text-gray-900">
                                                    Before Meal
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input
                                                    id="after-meal-{{ $index }}"
                                                    name="meal_instruction[{{ $index }}]"
                                                    value="after"
                                                    {{ old("meal_instruction.$index", $guideline['meal_instruction']) == 'after' ? 'checked' : '' }}
                                                    type="radio"
                                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                />
                                                <label for="after-meal-0" class="ml-2 block text-sm text-gray-900">
                                                    After Meal
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="duration-0" class="block text-sm font-medium text-gray-700">
                                            Duration
                                        </label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input
                                                type="number"
                                                name="duration[]"
                                                id="duration-0"
                                                placeholder="7"
                                                value="{{ $guideline['duration_days'] }}"
                                                class="block w-full flex-1 rounded-none rounded-l-md border-gray-300 p-3 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            />
                                            <span
                                                class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500"
                                            >
                                                days
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of medicine container -->
                    @endforeach
                @endif
            </div>

            <button
                type="button"
                id="add-medicine-btn"
                class="inline-flex items-center rounded-md border border-dashed border-gray-400 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
            >
                <svg
                    class="mr-2 h-5 w-5 text-gray-500"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd"
                    />
                </svg>
                Add More Medicine
            </button>

            <div
                id="medicine-template"
                class="medicine-entry relative hidden rounded-xl border border-gray-200 bg-white p-6 shadow-lg"
            >
                <!-- Remove Button -->
                <button
                    type="button"
                    class="remove-medicine-btn absolute top-4 right-4 text-gray-400 transition-colors hover:text-red-500"
                >
                    <svg
                        class="h-6 w-6"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </button>

                <h3 class="template-title mb-4 text-lg font-semibold text-indigo-600">Medicine #X</h3>
                <div class="space-y-6">
                    <!-- Medicine Name -->
                    <div>
                        <label for="medicine-name-template" class="block text-sm font-medium text-gray-700">
                            Medicine Name
                        </label>
                        <input
                            type="text"
                            name="medicine_name[]"
                            id="medicine-name-template"
                            placeholder="e.g., Napa Extend"
                            disabled
                            class="mt-1 block w-full rounded-md border-gray-300 p-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                    </div>
                    <!-- Dosage and Frequency -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="md:col-span-1">
                            <label for="dose-quantity-template" class="block text-sm font-medium text-gray-700">
                                Dose
                            </label>
                            <input
                                type="text"
                                name="dose_quantity[]"
                                id="dose-quantity-template"
                                value="১ টা করে"
                                disabled
                                class="mt-1 block w-full rounded-md border-gray-300 p-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Frequency</label>
                            <div class="mt-2 flex items-center space-x-4 sm:space-x-6">
                                <div class="flex items-center">
                                    <input
                                        id="morning-template"
                                        name="frequency[TPL][]"
                                        value="morning"
                                        type="checkbox"
                                        disabled
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <label for="morning-template" class="ml-2 block text-sm text-gray-900">
                                        Morning
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input
                                        id="noon-template"
                                        name="frequency[TPL][]"
                                        value="noon"
                                        type="checkbox"
                                        disabled
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <label for="noon-template" class="ml-2 block text-sm text-gray-900">Noon</label>
                                </div>
                                <div class="flex items-center">
                                    <input
                                        id="night-template"
                                        name="frequency[TPL][]"
                                        value="night"
                                        type="checkbox"
                                        disabled
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <label for="night-template" class="ml-2 block text-sm text-gray-900">Night</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Meal Instruction & Duration -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meal Instruction</label>
                            <div class="mt-2 flex items-center space-x-6">
                                <div class="flex items-center">
                                    <input
                                        id="before-meal-template"
                                        name="meal_instruction[TPL]"
                                        value="before"
                                        disabled
                                        type="radio"
                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <label for="before-meal-template" class="ml-2 block text-sm text-gray-900">
                                        Before Meal
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input
                                        id="after-meal-template"
                                        name="meal_instruction[TPL]"
                                        value="after"
                                        type="radio"
                                        disabled
                                        checked
                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <label for="after-meal-template" class="ml-2 block text-sm text-gray-900">
                                        After Meal
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="duration-template" class="block text-sm font-medium text-gray-700">
                                Duration
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input
                                    type="number"
                                    name="duration[]"
                                    id="duration-template"
                                    placeholder="7"
                                    disabled
                                    class="block w-full flex-1 rounded-none rounded-l-md border-gray-300 p-3 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                                <span
                                    class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500"
                                >
                                    days
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- medicine dose guideline section --}}

            {{-- advices section --}}

            <div id="advice-fields-container" class="space-y-4">
                <!-- Initial Advice Field (Template) -->
                @if ($prescription->advices != null)
                    @foreach ($prescription->advices as $advice)
                        <div class="advice-entry flex items-center space-x-3">
                            <textarea
                                name="advices[]"
                                class="w-full flex-grow rounded-lg border border-gray-300 p-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                rows="2"
                                placeholder="e.g., Always be learning..."
                                required
                            >
{{ $advice }}</textarea
                            >
                            <button
                                type="button"
                                class="remove-advice-btn rounded-lg bg-red-500 p-3 text-white shadow transition hover:bg-red-600"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col space-y-3 sm:flex-row sm:justify-between sm:space-y-0">
                <button
                    type="button"
                    id="add-advice-btn"
                    class="inline-flex items-center rounded-md border border-dashed border-gray-400 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                >
                    + Add Another Advice
                </button>
            </div>
            {{-- advices section --}}

            <div>
                <label for="report_images" class="block text-sm font-medium text-gray-700">Report Images</label>
                <input
                    type="file"
                    name="report_images[]"
                    id="report_images"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                    accept="image/*"
                    multiple
                />
                @php
                    $reports = is_array($prescription->report_images) ? $prescription->report_images : json_decode($prescription->report_images ?? '[]', true);
                @endphp

                <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-5">
                    @foreach ($reports as $img)
                        <div>
                            <x-image
                                image="{{ $img }}"
                                alt="Report image"
                                class="rounded-md border object-cover"
                                deleteRoute="{{ route('dashboard.prescriptions.image-delete', $prescription->id) }}"
                            />
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 text-right">
                <button
                    type="submit"
                    class="inline-flex items-center rounded bg-green-600 px-5 py-2 text-sm font-medium text-white transition hover:bg-green-700 focus:ring-2 focus:ring-green-400 focus:outline-none"
                >
                    ✏️ Update Prescription
                </button>
            </div>
        </form>
    </div>
@endsection

<script>
    @section('script')
        function initMultiSelect({ containerSelector, options, inputName, optionKey = 'name', initialSelected = [] }) {
            const $container = $(containerSelector);
            const $searchInput = $container.find('.multiselect-search');
            const $dropdown = $container.find('.multiselect-dropdown');
            const $selectedContainer = $container.find('.multiselect-selected');
            const $hiddenInputs = $container.find('.multiselect-hidden-inputs');

            let selected = [];

            // Initialize selected from initialSelected param
            initialSelected.forEach((val) => {
                const found = options.find((o) => o[optionKey] === val);
                if (found) selected.push(found);
            });

            function renderDropdown() {
                const searchText = $searchInput.val().toLowerCase();
                let filtered = options.filter((o) => o[optionKey].toLowerCase().includes(searchText));

                let html = '';
                if (filtered.length === 0) {
                    html = '<div class="px-3 py-2 text-gray-500">No options found</div>';
                } else {
                    filtered.forEach((option) => {
                        const isSelected = selected.some((s) => s[optionKey] === option[optionKey]);
                        html += `
                    <div class="cursor-pointer px-3 py-2 hover:bg-blue-100 flex items-center ${isSelected ? 'bg-blue-200' : ''}">
                        <input type="checkbox" class="mr-2" ${isSelected ? 'checked' : ''} />
                        <span>${option[optionKey]}</span>
                    </div>
                `;
                    });
                }
                $dropdown.html(html).show();
            }

            function renderSelected() {
                if (selected.length === 0) {
                    $selectedContainer.html('<div class="text-gray-400 text-sm">No selections</div>');
                    $hiddenInputs.empty();
                    return;
                }

                let pillsHtml = '';
                let hiddenHtml = '';

                selected.forEach((item) => {
                    pillsHtml += `
                <span class="inline-flex items-center rounded bg-blue-100 px-2 py-1 text-xs text-blue-700 mr-1 mb-1">
                    ${item[optionKey]}
                    <button type="button" class="text-lg ml-1 text-blue-500 hover:text-blue-700 remove-selected" data-key="${item[optionKey]}">×</button>
                </span>
            `;

                    hiddenHtml += `<input type="hidden" name="${inputName}" value="${item[optionKey]}">`;
                });

                $selectedContainer.html(pillsHtml);
                $hiddenInputs.html(hiddenHtml);
            }

            $searchInput.on('input', renderDropdown);
            $searchInput.on('focus', renderDropdown);

            $(document).on('click', function (e) {
                if (!$(e.target).closest(containerSelector).length) {
                    $dropdown.hide();
                }
            });

            $dropdown.on('click', 'div', function () {
                const name = $(this).find('span').text();
                const option = options.find((o) => o[optionKey] === name);
                if (!option) return;

                const index = selected.findIndex((s) => s[optionKey] === name);
                if (index > -1) {
                    selected.splice(index, 1);
                } else {
                    selected.push(option);
                }
                renderSelected();
                renderDropdown();
                $searchInput.val('');
                $dropdown.hide();
            });

            $selectedContainer.on('click', '.remove-selected', function () {
                const key = $(this).data('key');
                const index = selected.findIndex((s) => s[optionKey] === key);
                if (index > -1) {
                    selected.splice(index, 1);
                    renderSelected();
                    renderDropdown();
                }
            });

            renderSelected();
        }

        $(document).ready(function () {
            initMultiSelect({
                containerSelector: '#complaints-multiselect',
                options: @json(\App\Models\Complaint::all(['complaint'])),
                inputName: 'complaints[]',
                optionKey: 'complaint',
                initialSelected: JSON.parse($('#complaints-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#present-illness-multiselect',
                options: @json(\App\Models\IllnessModel::all(['illness'])),
                inputName: 'present_illness[]',
                optionKey: 'illness',
                initialSelected: JSON.parse($('#present-illness-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#past-illness-multiselect',
                options: @json(\App\Models\IllnessModel::all(['illness'])),
                inputName: 'past_illness[]',
                optionKey: 'illness',
                initialSelected: JSON.parse($('#past-illness-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#history-of-medication-multiselect',
                options: @json(\App\Models\Medicine::all(['name'])),
                inputName: 'history_of_medication[]',
                optionKey: 'name',
                initialSelected: JSON.parse($('#history-of-medication-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#investigations-multiselect',
                options: @json(\App\Models\Investigation::all(['investigation'])),
                inputName: 'investigations[]',
                optionKey: 'investigation',
                initialSelected: JSON.parse($('#investigations-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#history-of-concomitant-illness-multiselect',
                options: @json(\App\Models\ConcomitantDiseases::all(['name'])),
                inputName: 'history_of_concomitant_illness[]',
                optionKey: 'name',
                initialSelected: JSON.parse(
                    $('#history-of-concomitant-illness-multiselect').attr('data-selected') || '[]',
                ),
            });

            initMultiSelect({
                containerSelector: '#general-examinations-multiselect',
                options: @json(\App\Models\Examination::where('type', 'general')->get()),
                inputName: 'general_examinations[]',
                optionKey: 'examination',
                initialSelected: JSON.parse($('#general-examinations-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#dermatological-examinations-multiselect',
                options: @json(\App\Models\Examination::where('type', 'dermatological')->get()),
                inputName: 'dermatological_examinations[]',
                optionKey: 'examination',
                initialSelected: JSON.parse(
                    $('#dermatological-examinations-multiselect').attr('data-selected') || '[]',
                ),
            });

            initMultiSelect({
                containerSelector: '#systemic-examinations-multiselect',
                options: @json(\App\Models\Examination::where('type', 'systemic')->get()),
                inputName: 'systemic_examinations[]',
                optionKey: 'examination',
                initialSelected: JSON.parse($('#systemic-examinations-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#menstrual-history-multiselect',
                options: @json(\App\Models\MenstrualDiseas::all(['name'])),
                inputName: 'menstrual_history[]',
                optionKey: 'name',
                initialSelected: JSON.parse($('#menstrual-history-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#personal-history-multiselect',
                options: @json(\App\Models\PersonalInfo::all(['name'])),
                inputName: 'personal_history[]',
                optionKey: 'name',
                initialSelected: JSON.parse($('#personal-history-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#site-involvement-multiselect',
                options: @json(\App\Models\AssociatedSite::all(['site'])),
                inputName: 'site_involvement[]',
                optionKey: 'site',
                initialSelected: JSON.parse($('#site-involvement-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#family-disease-history-multiselect',
                options: @json(\App\Models\ConcomitantDiseases::all(['name'])),
                inputName: 'family_disease_history[]',
                optionKey: 'name',
                initialSelected: JSON.parse($('#family-disease-history-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#special-procedures-multiselect',
                options: @json(\App\Models\SpecialProcedure::all(['procedure'])),
                inputName: 'special_procedures[]',
                optionKey: 'procedure',
                initialSelected: JSON.parse($('#special-procedures-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#vaccination-history-multiselect',
                options: @json(\App\Models\Vaccination::all(['name'])),
                inputName: 'vaccination_history[]',
                optionKey: 'name',
                initialSelected: JSON.parse($('#vaccination-history-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#obstetric-history-multiselect',
                options: @json(\App\Models\Obstetric::all(['name'])),
                inputName: 'obstetric_history[]',
                optionKey: 'name',
                initialSelected: JSON.parse($('#obstetric-history-multiselect').attr('data-selected') || '[]'),
            });

            initMultiSelect({
                containerSelector: '#operational-history-multiselect',
                options: @json(\App\Models\Operation::all(['name'])),
                inputName: 'operational_history[]',
                optionKey: 'name',
                initialSelected: JSON.parse($('#operational-history-multiselect').attr('data-selected') || '[]'),
            });
        });

    $(document).ready(function() {
    let medicineIndex = {{ count($prescription->medication_guidelines ?? []) }}; // Start with 1 since we already have one entry

    // Handle "Add More Medicine" button click
     $('#add-medicine-btn').click(function() {
        // 1. Clone the hidden and disabled template
        let newEntry = $('#medicine-template').clone();

        // 2. Remove the id and 'hidden' class to make the cloned div visible
        newEntry.removeAttr('id').removeClass('hidden');

        // ... (Your existing code to update title, IDs, and names) ...
        newEntry.find('.template-title').text('Medicine #' + (medicineIndex + 1));
        newEntry.find('input, label').each(function() {
            let oldId = $(this).attr('id');
            if (oldId) $(this).attr('id', oldId.replace('-template', '-' + medicineIndex));

            let oldFor = $(this).attr('for');
            if (oldFor) $(this).attr('for', oldFor.replace('-template', '-' + medicineIndex));

            let oldName = $(this).attr('name');
            if (oldName) $(this).attr('name', oldName.replace('[TPL]', '[' + medicineIndex + ']'));
        });

        // 3. !! IMPORTANT: Re-enable all inputs within the new cloned entry !!
        // This makes them active and part of the form submission.
        newEntry.find('input').prop('disabled', false);

        // 4. Append the new, active entry to the container
        $('#medicine-fields-container').append(newEntry);

        medicineIndex++;
    });

    // Handle "Remove" button click (using event delegation for dynamic elements)
    $('#medicine-fields-container').on('click', '.remove-medicine-btn', function() {
        // Find the closest parent '.medicine-entry' and remove it
        $(this).closest('.medicine-entry').remove();
    });
});

$(document).ready(function() {
            const newFieldHtml = `
                <div class="flex items-center space-x-3 advice-entry" style="display: none;">
                    <textarea 
                        name="advices[]"
                        class="flex-grow w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        rows="2"
                        placeholder="Another piece of advice..."
                        required
                    ></textarea>
                    <button type="button" class="remove-advice-btn bg-red-500 text-white p-3 rounded-lg hover:bg-red-600 transition shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>`;

            // --- Event Handler to ADD a new advice field ---
            $('#add-advice-btn').on('click', function() {
                // Create a new field from the template, hide it, append it, then slide it down.
                const newField = $(newFieldHtml);
                $('#advice-fields-container').append(newField);
                newField.slideDown(300); // 300ms animation
            });

            // --- Event Handler to REMOVE an advice field ---
            // We use event delegation on the container because fields are added dynamically.
            // This ensures the click handler works for new fields too.
            $('#advice-fields-container').on('click', '.remove-advice-btn', function() {
                // Find the parent .advice-entry and animate its removal.
                // We use slideUp and then remove the element in the callback to ensure
                // the animation completes before the element is gone.
                $(this).closest('.advice-entry').slideUp(300, function() {
                    $(this).remove();
                });
            });
        });
@endsection
</script>
