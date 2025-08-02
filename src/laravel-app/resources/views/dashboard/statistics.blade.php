@extends('dashboard-layout')

@php
    $avg = $patientsCount->avg_patients;
    $old = $patientsCount->old;
    $new = $patientsCount->new;

    $total = $avg + $old + $new;
    if ($total === 0) {
        $total = 1;
    }

    $oldRatio = ($old / $total) * 100;
    $avgRatio = ($avg / $total) * 100;
    $newRatio = ($new / $total) * 100;

    $oldDash = $oldRatio;
    $avgDash = $avgRatio;
    $newDash = $newRatio;

    $oldOffset = 0;
    $avgOffset = -$oldDash;
    $newOffset = -($oldDash + $avgRatio);
@endphp

@section('content')
    <div class="h-full w-full">
        <div class="mx-auto mb-8 flex max-w-7xl items-center justify-between rounded-xl bg-white p-6 shadow">
            <div class="flex items-center gap-5">
                <!-- Modern Avatar with Ring -->
                <img
                    src="https://tuk-cdn.s3.amazonaws.com/assets/components/avatars/a_3_7.png"
                    alt="Doctor Profile Picture"
                    class="h-20 w-20 rounded-full object-cover ring-4 ring-blue-100"
                />

                <!-- Text with a Left Accent Border -->
                <div class="border-l-4 border-blue-500 pl-5">
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $doctorName ?? 'Dr. Shahadev Kumar Adhikari' }}
                    </h2>

                    <!-- Truncate long text to prevent wrapping issues -->
                    <p class="mt-1 max-w-lg text-gray-500">
                        {{ $doctorDesignation ?? 'MBBS, BCS ( Health), FCPS( Dermatology), DDV(BSMMU) Post Graduation Diploma  in cosmetics and Laser surgery (India)' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl space-y-6 pb-10">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Patients Card -->
                <div class="bg-stat-appointments rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="mb-1 text-3xl font-bold">{{ $patientsCount->total }}</h3>
                            <p class="text-base font-medium opacity-90 md:text-lg">Patients</p>
                        </div>
                        <i class="fas fa-user-injured text-2xl opacity-80 md:text-4xl"></i>
                    </div>
                </div>

                <!-- Examinations Card -->
                <div class="bg-stat-consultancy rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="mb-1 text-3xl font-bold">{{ $examinationsCount }}</h3>
                            <p class="text-base font-medium opacity-90 md:text-lg">Examinations</p>
                        </div>
                        <i class="fa-solid fa-flask-vial text-2xl opacity-80 md:text-4xl"></i>
                    </div>
                </div>

                <!-- Investigations Card -->
                <div class="bg-stat-pending rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="mb-1 text-3xl font-bold">{{ $investigationsCount }}</h3>
                            <p class="text-base font-medium opacity-90 md:text-lg">Investigations</p>
                        </div>
                        <i class="fa-solid fa-microscope text-2xl opacity-80 md:text-4xl"></i>
                    </div>
                </div>

                <!-- Medicine Card -->
                <div class="bg-stat-request rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="mb-1 text-3xl font-bold">{{ $medicinesCount }}</h3>
                            <p class="text-base font-medium opacity-90 md:text-lg">Medicines</p>
                        </div>
                        <i class="fa-solid fa-pills text-2xl opacity-80 md:text-4xl"></i>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Patient Details -->
                <div class="flex flex-col rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Patient Details</h3>
                    </div>

                    <div class="flex flex-1 flex-col justify-between space-y-4 p-6">
                        @foreach ($latestPatients as $patient)
                            <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-500 font-medium text-white"
                                    >
                                        <img
                                            class="h-full w-full object-cover"
                                            src="{{ $patient->profile_picture }}"
                                            alt="{{ $patient->name }}"
                                        />
                                    </div>
                                    <div class="flex w-full items-center justify-between">
                                        <a
                                            class="hover:underline"
                                            href="{{ route('dashboard.patients.details', $patient->id) }}"
                                        >
                                            <h4 class="font-medium text-gray-900">{{ $patient->name }}</h4>
                                        </a>
                                        <div class="space-y-1 text-sm text-gray-500">
                                            <div>Sex: {{ ucfirst(substr($patient->gender, 0, 1)) }}</div>
                                            <div>Age: {{ $patient->age }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <h5 class="mb-1 text-sm font-medium text-gray-900">Last Prescription</h5>
                                <a
                                    href="{{ route('dashboard.patients') }}"
                                    class="text-chart-primary text-sm hover:underline"
                                >
                                    See more
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patients Pie Schedule -->
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Patients Chart</h3>
                    </div>

                    <div class="p-6">
                        <!-- Pie Chart -->
                        <div class="relative mx-auto mb-4 h-[220px] w-[220px]">
                            <svg class="h-full w-full -rotate-90 transform" viewBox="0 0 42 42">
                                <!-- Background circle -->
                                <circle
                                    cx="21"
                                    cy="21"
                                    r="15.915"
                                    fill="transparent"
                                    stroke="#e5e7eb"
                                    stroke-width="3"
                                ></circle>

                                <!-- Old Patients (Segment 1) -->
                                <circle
                                    cx="21"
                                    cy="21"
                                    r="15.915"
                                    fill="transparent"
                                    stroke="#6366f1"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $oldDash }} {{ 100 - $oldDash }}"
                                    stroke-dashoffset="{{ $oldOffset }}"
                                ></circle>

                                <!-- Average Patients (Segment 2) -->
                                <circle
                                    cx="21"
                                    cy="21"
                                    r="15.915"
                                    fill="transparent"
                                    stroke="red"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $avgDash }} {{ 100 - $avgDash }}"
                                    stroke-dashoffset="{{ $avgOffset }}"
                                ></circle>

                                <!-- New Patients (Segment 3) -->
                                <circle
                                    cx="21"
                                    cy="21"
                                    r="15.915"
                                    fill="transparent"
                                    stroke="#10b981"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $newDash }} {{ 100 - $newDash }}"
                                    stroke-dashoffset="{{ $newOffset }}"
                                ></circle>
                            </svg>

                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <!-- You might want to make this dynamic -->
                                    <div class="text-xl font-bold">
                                        {{ $patientsCount->new + $patientsCount->old }}
                                    </div>
                                    <div class="text-xs text-gray-500">Total Patients</div>
                                </div>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <!-- Use the actual color class here -->
                                    <div class="h-3 w-3 rounded bg-indigo-500"></div>
                                    <span>Old Patient</span>
                                </div>
                                <span>{{ $patientsCount->old }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <!-- Use the actual color class here -->
                                    <div class="h-3 w-3 rounded bg-red-500"></div>
                                    <span>Average Patient</span>
                                </div>
                                <span>{{ round($patientsCount->avg_patients) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <!-- Corrected color to match chart -->
                                    <div class="h-3 w-3 rounded bg-emerald-500"></div>
                                    <span>New Patient</span>
                                </div>
                                <span>{{ $patientsCount->new }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patient Analysis -->
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Patient Analysis</h3>
                    </div>

                    <div id="patient-analysis-chart-container" class="p-6">
                        <!-- Main Chart Structure -->
                        <div id="patient-chart" class="relative flex min-h-[300px]">
                            <!-- Y-Axis Labels (will be populated by JS) -->
                            <div
                                id="patient-y-axis"
                                class="flex flex-col-reverse justify-between pr-4 text-right text-sm text-gray-500"
                            >
                                <!-- JS will generate: <div>100</div> -->
                            </div>

                            <!-- Bars and X-Axis -->
                            <div class="flex w-full flex-col border-b border-l border-gray-300">
                                <!-- Bars Container -->
                                <div
                                    id="patient-bars-container"
                                    class="flex h-full w-full flex-grow items-end justify-around gap-x-2 px-2"
                                >
                                    <!-- Bars will be generated by JS -->
                                </div>

                                <!-- X-Axis Labels Container -->
                                <div
                                    id="patient-x-axis"
                                    class="mt-2 flex w-full justify-around border-t border-gray-200 pt-2 text-sm text-gray-600"
                                >
                                    <!-- Labels will be generated by JS -->
                                </div>
                            </div>
                        </div>
                        <!-- Tooltip (initially hidden) -->
                        <div
                            id="patient-tooltip"
                            class="pointer-events-none absolute z-10 hidden rounded bg-gray-800 px-2 py-1 text-xs text-white"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    const chartData =
    @json($patientData)
    ; const chartConfig = { yAxisTicks: 5, barColor: 'bg-indigo-500', barHoverColor: 'bg-indigo-600',
    tooltipValuePrefix: 'Patients: ', }; function createPatientChart(data, config) { const yAxis =
    document.getElementById('patient-y-axis'); const barsContainer = document.getElementById('patient-bars-container');
    const xAxis = document.getElementById('patient-x-axis'); const tooltip = document.getElementById('patient-tooltip');
    yAxis.innerHTML = ''; barsContainer.innerHTML = ''; xAxis.innerHTML = ''; if (!data || data.length === 0) {
    barsContainer.innerHTML = '
    <p class="w-full text-center text-gray-500">No data available to display.</p>
    '; return; } const maxValue = Math.max(...data.map((item) => item.value)); const yMax = Math.ceil(maxValue / 10) *
    10; for (let i = 0; i <= config.yAxisTicks; i++) { const value = Math.round((yMax / config.yAxisTicks) * i); const
    labelDiv = document.createElement('div'); labelDiv.textContent = value; yAxis.appendChild(labelDiv); }
    data.forEach((item) => { const barHeightPercentage = yMax > 0 ? (item.value / yMax) * 100 : 0; const bar =
    document.createElement('div'); bar.className = `w-full rounded-t ${config.barColor} hover:${config.barHoverColor}
    transition-all duration-300 ease-in-out cursor-pointer`; bar.style.height = `${barHeightPercentage}%`;
    bar.dataset.value = item.value; barsContainer.appendChild(bar); const label = document.createElement('div');
    label.className = 'text-center w-full'; label.textContent = item.label; xAxis.appendChild(label);
    bar.addEventListener('mousemove', (e) => { tooltip.classList.remove('hidden'); tooltip.style.left = `${e.pageX +
    10}px`; tooltip.style.top = `${e.pageY - 30}px`; tooltip.textContent =
    `${config.tooltipValuePrefix}${bar.dataset.value}`; }); bar.addEventListener('mouseleave', () => {
    tooltip.classList.add('hidden'); }); }); } createPatientChart(chartData, chartConfig);
@endsection

{{--
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 p-6">
    <div class="flex items-center justify-between">
    <h3 class="text-lg font-semibold text-gray-900">Today's Appointment</h3>
    <a href="#" class="text-chart-primary text-sm font-medium hover:underline">See all</a>
    </div>
    </div>
    <div class="space-y-4 p-6">
    <!-- Appointment Item 1 -->
    <div class="flex items-center justify-between rounded-lg p-3 transition-colors hover:bg-gray-50">
    <div class="flex items-center gap-3">
    <div
    class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 text-xs font-medium text-white"
    >
    MJ
    </div>
    <div>
    <p class="text-sm font-medium text-gray-900">M.J Kumar</p>
    <p class="text-xs text-gray-500">Health Checkup</p>
    </div>
    </div>
    <span class="bg-status-ongoing rounded-full px-2 py-1 text-xs text-white">Ongoing</span>
    </div>
    
    <!-- Appointment Item 2 -->
    <div class="flex items-center justify-between rounded-lg p-3 transition-colors hover:bg-gray-50">
    <div class="flex items-center gap-3">
    <div
    class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-xs font-medium text-white"
    >
    RK
    </div>
    <div>
    <p class="text-sm font-medium text-gray-900">Rishi Kiran</p>
    <p class="text-xs text-gray-500">Heavy Cold</p>
    </div>
    </div>
    <span class="text-sm font-medium text-gray-600">12:30 PM</span>
    </div>
    </div>
    </div>
    
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 p-6">
    <div class="flex items-center justify-between">
    <h3 class="text-lg font-semibold text-gray-900">Appointment Request</h3>
    <a href="#" class="text-chart-primary text-sm font-medium hover:underline">See all</a>
    </div>
    </div>
    <div class="overflow-hidden">
    <!-- Table Header -->
    <div class="grid grid-cols-4 gap-4 border-b border-gray-200 bg-gray-50 p-3 text-sm font-medium text-gray-700">
    <span>Name</span>
    <span>Date</span>
    <span>Time</span>
    <span>Action</span>
    </div>
    
    <!-- Table Rows -->
    <div class="divide-y divide-gray-200">
    <div class="grid grid-cols-4 items-center gap-4 p-3">
    <div class="flex items-center gap-2">
    <div
    class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-purple-500 text-xs text-white"
    >
    V
    </div>
    <span class="text-sm text-gray-900">Venkatesh</span>
    </div>
    <span class="text-sm text-gray-600">19 Jan</span>
    <span class="text-sm text-gray-600">01:00PM</span>
    <div class="flex gap-2">
    <button
    class="flex h-8 w-8 items-center justify-center rounded border border-green-500 text-green-500 transition-colors hover:bg-green-500 hover:text-white"
    >
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path
    stroke-linecap="round"
    stroke-linejoin="round"
    stroke-width="2"
    d="M5 13l4 4L19 7"
    ></path>
    </svg>
    </button>
    <button
    class="flex h-8 w-8 items-center justify-center rounded border border-red-500 text-red-500 transition-colors hover:bg-red-500 hover:text-white"
    >
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path
    stroke-linecap="round"
    stroke-linejoin="round"
    stroke-width="2"
    d="M6 18L18 6M6 6l12 12"
    ></path>
    </svg>
    </button>
    </div>
    </div>
    
    <div class="grid grid-cols-4 items-center gap-4 p-3">
    <div class="flex items-center gap-2">
    <div
    class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-500 text-xs text-white"
    >
    RK
    </div>
    <span class="text-sm text-gray-900">Rishi Kiran</span>
    </div>
    <span class="text-sm text-gray-600">14 Jan</span>
    <span class="text-sm text-gray-600">02:00PM</span>
    <div class="flex gap-2">
    <button
    class="flex h-8 w-8 items-center justify-center rounded border border-green-500 text-green-500 transition-colors hover:bg-green-500 hover:text-white"
    >
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path
    stroke-linecap="round"
    stroke-linejoin="round"
    stroke-width="2"
    d="M5 13l4 4L19 7"
    ></path>
    </svg>
    </button>
    <button
    class="flex h-8 w-8 items-center justify-center rounded border border-red-500 text-red-500 transition-colors hover:bg-red-500 hover:text-white"
    >
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path
    stroke-linecap="round"
    stroke-linejoin="round"
    stroke-width="2"
    d="M6 18L18 6M6 6l12 12"
    ></path>
    </svg>
    </button>
    </div>
    </div>
    
    <div class="grid grid-cols-4 items-center gap-4 p-3">
    <div class="flex items-center gap-2">
    <div
    class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-orange-500 text-xs text-white"
    >
    CV
    </div>
    <span class="text-sm text-gray-900">Chinna Vel</span>
    </div>
    <span class="text-sm text-gray-600">15 Jan</span>
    <span class="text-sm text-gray-600">12:00 PM</span>
    <div class="flex gap-2">
    <button
    class="flex h-8 w-8 items-center justify-center rounded border border-green-500 text-green-500 transition-colors hover:bg-green-500 hover:text-white"
    >
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path
    stroke-linecap="round"
    stroke-linejoin="round"
    stroke-width="2"
    d="M5 13l4 4L19 7"
    ></path>
    </svg>
    </button>
    <button
    class="flex h-8 w-8 items-center justify-center rounded border border-red-500 text-red-500 transition-colors hover:bg-red-500 hover:text-white"
    >
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path
    stroke-linecap="round"
    stroke-linejoin="round"
    stroke-width="2"
    d="M6 18L18 6M6 6l12 12"
    ></path>
    </svg>
    </button>
    </div>
    </div>
    </div>
    </div>
    </div>
    
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900">Appointment Timeline</h3>
    </div>
    <div class="space-y-4 p-6">
    <!-- Timeline Item 1 -->
    <div class="flex items-center gap-3">
    <div class="bg-status-ongoing h-3 w-3 rounded-full"></div>
    <div class="flex-1">
    <div class="flex items-center gap-2 text-sm">
    <span class="font-medium">11:30AM</span>
    <span class="text-gray-400">|</span>
    <span>Clinic Consulting</span>
    </div>
    </div>
    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-xs text-white">PA</div>
    </div>
    
    <!-- Timeline Item 2 -->
    <div class="flex items-center gap-3">
    <div class="h-3 w-3 rounded-full bg-gray-300"></div>
    <div class="flex-1">
    <div class="flex items-center gap-2 text-sm">
    <span class="font-medium">02:30PM</span>
    <span class="text-gray-400">|</span>
    <span>Online Consulting</span>
    </div>
    </div>
    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-xs text-white">PB</div>
    </div>
    
    <!-- Timeline Item 3 -->
    <div class="flex items-center gap-3">
    <div class="h-3 w-3 rounded-full bg-gray-300"></div>
    <div class="flex-1">
    <div class="flex items-center gap-2 text-sm">
    <span class="font-medium">05:30PM</span>
    <span class="text-gray-400">|</span>
    <span>Meeting - Dr.Sam</span>
    </div>
    </div>
    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 text-xs text-white">DS</div>
    </div>
    </div>
    </div>
--}}

