@extends('dashboard-layout')

@section('content')
    <div class="mx-auto mt-10 max-w-3xl rounded-xl bg-white p-6 shadow-md">
        <h2 class="mb-6 text-2xl font-semibold">
            <i class="fa-solid fa-pen-to-square"></i>
            Edit Patient
        </h2>

        <form
            action="{{ route('dashboard.patients.update', $patient->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="grid grid-cols-1 gap-6 md:grid-cols-2"
        >
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $patient->name) }}"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                />
            </div>

            {{-- Age --}}
            <div>
                <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                <input
                    type="number"
                    name="age"
                    id="age"
                    value="{{ old('age', $patient->age) }}"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                    min="0"
                />
            </div>

            {{-- Date of Birth --}}
            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input
                    type="date"
                    name="date_of_birth"
                    id="date_of_birth"
                    value="{{ old('date_of_birth', $patient->date_of_birth) }}"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                />
            </div>

            {{-- Gender --}}
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select
                    name="gender"
                    id="gender"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                >
                    <option value="" disabled>Select</option>
                    @foreach (['male', 'female', 'other'] as $gender)
                        <option value="{{ $gender }}" @selected(old('gender', $patient->gender) === $gender)>
                            {{ ucfirst($gender) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input
                    type="text"
                    name="phone"
                    id="phone"
                    value="{{ old('phone', $patient->phone) }}"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                />
            </div>

            {{-- Occupation --}}
            <div>
                <label for="occupation" class="block text-sm font-medium text-gray-700">Occupation</label>
                <input
                    type="text"
                    name="occupation"
                    id="occupation"
                    value="{{ old('occupation', $patient->occupation) }}"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                />
            </div>

            {{-- Address --}}
            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea
                    name="address"
                    id="address"
                    rows="2"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                >
                    {{ old('address', $patient->address) }}
                </textarea>
            </div>

            {{-- Religion --}}
            <div>
                <label for="religion" class="block text-sm font-medium text-gray-700">Religion</label>
                <input
                    type="text"
                    name="religion"
                    id="religion"
                    value="{{ old('religion', $patient->religion) }}"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                />
            </div>

            {{-- Blood Group --}}
            <div>
                <label for="blood_group" class="block text-sm font-medium text-gray-700">Blood Group</label>
                <select
                    name="blood_group"
                    id="blood_group"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                >
                    <option value="" disabled>Select</option>
                    @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group)
                        <option value="{{ $group }}" @selected(old('blood_group', $patient->blood_group) === $group)>
                            {{ $group }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Profile Picture --}}
            <div>
                <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                <input
                    type="file"
                    name="profile_picture"
                    id="profile_picture"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                    accept="image/*"
                />
                @if ($patient->profile_picture)
                    <img
                        src="{{ asset($patient->profile_picture) }}"
                        class="mt-2 h-24 w-24 rounded-md border object-cover"
                        alt="Current Picture"
                    />
                @endif
            </div>

            {{-- Report Images --}}
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
                    $reports = is_array($patient->report_images) ? $patient->report_images : json_decode($patient->report_images ?? '[]', true);
                @endphp

                <div class="mt-2 grid grid-cols-2 gap-2">
                    @foreach ($reports as $img)
                        <img src="{{ asset($img) }}" class="h-24 w-24 rounded-md border object-cover" alt="Report" />
                    @endforeach
                </div>
            </div>

            {{-- Submit --}}
            <div class="text-right md:col-span-2">
                <button type="submit" class="rounded-md bg-blue-600 px-6 py-2 text-white transition hover:bg-blue-700">
                    💾 Update Patient
                </button>
            </div>
        </form>
    </div>
@endsection
