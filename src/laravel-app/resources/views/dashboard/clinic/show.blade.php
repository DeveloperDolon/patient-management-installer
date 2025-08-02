@extends('dashboard-layout')

@section('content')
    <div class="mx-auto mt-10 max-w-3xl rounded-xl bg-white p-6 shadow-md">
        <div class="flex justify-between items-center">
            <h2 class="mb-6 text-2xl font-semibold">Clinic Details</h2>
            <button class="text-blue-600 hover:underline">
                <a href="{{ route('dashboard.clinics') }}" class="text-blue-600 hover:underline">Back to Clinics</a>
            </button>
        </div>
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <img src="{{ asset($clinic->logo) }}" alt="{{ $clinic->clinic_name }} Logo" class="h-24 w-24 rounded-md border border-gray-300 object-cover">
            </div>

            <div class="flex flex-col justify-end">
                <label class="block text-base font-bold text-black">Clinic Name</label>
                <p class="mt-1 text-gray-900">{{ $clinic->clinic_name }}</p>
            </div>

            <div>
                <label class="block text-base font-bold text-black">Clinic Address</label>
                <p class="mt-1 text-gray-900">{{ $clinic->clinic_address ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-base font-bold text-black">Clinic Email</label>
                <p class="mt-1 text-gray-900">{{ $clinic->clinic_email ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-base font-bold text-black">Phone Number</label>
                <p class="mt-1 text-gray-900">{{ $clinic->clinic_phone ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-base font-bold text-black">Visit Time</label>
                <p class="mt-1 text-gray-900">{{ $clinic->visit_time ?? 'N/A' }}</p>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('dashboard.clinics.edit', $clinic->id) }}" class="text-blue-600 hover:underline">Edit Clinic</a>
            </div>
        </div>
    </div>
@endsection
