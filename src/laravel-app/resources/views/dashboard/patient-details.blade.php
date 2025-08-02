@extends('dashboard-layout')

@section('content')
    <div class="mx-auto mt-10 max-w-4xl rounded-xl bg-white p-8 shadow-md">
        <div class="flex items-center justify-between">
            <h2 class="mb-6 text-2xl font-bold text-gray-800">
                <i class="fa-solid fa-hospital-user"></i>
                Patient Details
            </h2>

            <a href="{{ route('dashboard.patients.edit', ['id' => $patient->id]) }}">
                <button
                    class="inline cursor-pointer rounded-lg bg-indigo-500 px-2 py-1 text-sm text-white md:text-base"
                >
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="text-center md:col-span-2">
                @if ($patient->profile_picture)
                    <img
                        src="{{ asset($patient->profile_picture) }}"
                        alt="Profile Picture"
                        class="mx-auto mb-4 h-40 w-40 rounded-full border object-cover"
                    />
                @else
                    <div
                        class="mx-auto mb-4 flex h-40 w-40 items-center justify-center rounded-full bg-gray-200 text-gray-500"
                    >
                        No Image
                    </div>
                @endif
                <p class="text-xl font-semibold">{{ $patient->name }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Age</label>
                <p class="text-lg font-medium text-gray-800">{{ $patient->age }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Date of Birth</label>
                <p class="text-lg font-medium text-gray-800">{{ $patient->date_of_birth }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Gender</label>
                <p class="text-lg font-medium text-gray-800">{{ ucfirst($patient->gender) }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Phone</label>
                <p class="text-lg font-medium text-gray-800">{{ $patient->phone }}</p>
            </div>

            <div class="">
                <label class="text-sm text-gray-500">Address</label>
                <p class="text-lg font-medium text-gray-800">{{ $patient->address }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Occupation</label>
                <p class="text-lg font-medium text-gray-800">{{ $patient->occupation }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Religion</label>
                <p class="text-lg font-medium text-gray-800">{{ $patient->religion }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Blood Group</label>
                <p class="text-lg font-medium text-gray-800">{{ $patient->blood_group }}</p>
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-gray-500">Medical Reports</label>
                <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    @php
                        $reports = is_array($patient->report_images)
                            ? $patient->report_images
                            : json_decode($patient->report_images ?? '[]', true);
                    @endphp

                    @forelse ($reports as $img)
                        <img
                            src="{{ asset($img) }}"
                            alt="Report Image"
                            class="h-48 w-full rounded-md border object-cover"
                        />
                    @empty
                        <p class="col-span-2 text-gray-500">No report images available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
