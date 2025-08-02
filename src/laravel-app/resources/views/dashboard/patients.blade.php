@extends('dashboard-layout')

@section('content')
    <div class="w-full sm:px-6">
        <div class="rounded-tl-lg rounded-tr-lg bg-gray-100 px-4 py-4 md:px-10 md:py-7">
            <div class="items-center justify-center sm:flex">
                <p
                    tabindex="0"
                    class="text-base leading-normal font-bold text-gray-800 focus:outline-none sm:text-lg md:text-xl lg:text-2xl"
                >
                    Patient Management
                </p>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-5 md:mt-10 md:grid-cols-2 md:gap-20">
                <x-search
                    :data="[
                        'route' => 'dashboard.patients',
                        'search' => $search,
                    ]"
                />

                <div class="flex justify-end">
                    <a href="{{ route('dashboard.patients.create') }}">
                        <x-button
                            :data="[
                                'content' => 'Create New',
                            ]"
                        />
                    </a>
                </div>
            </div>
        </div>

        <div class="h-[calc(100vh-23rem)] overflow-y-auto bg-white px-4 pt-4 pb-5 shadow md:px-10 md:pt-7">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr tabindex="0" class="h-16 w-full text-sm leading-none text-gray-800 focus:outline-none">
                        <th class="text-left font-bold">Name</th>
                        <th class="text-left font-bold">Age</th>
                        <th class="text-left font-bold">Gender</th>
                        <th class="text-left font-bold">Blood Group</th>
                        <th class="text-left font-bold">Address</th>
                        <th class="px-4 text-left font-bold">Actions</th>
                    </tr>
                </thead>

                <tbody class="w-full">
                    @foreach ($patients as $patient)
                        <tr
                            tabindex="0"
                            class="h-12 border-t border-b border-gray-100 bg-white text-sm leading-none text-gray-800 hover:bg-gray-100 focus:outline-none"
                        >
                            <td class="">
                                <p class="font-medium">{{ $patient->name }}</p>
                            </td>

                            <td class="">
                                <p class="font-medium">{{ $patient->age }}</p>
                            </td>

                            <td class="">
                                <p class="font-medium">{{ $patient->gender }}</p>
                            </td>

                            <td class="">
                                <p class="font-medium">{{ $patient->blood_group }}</p>
                            </td>

                            <td class="">
                                <p class="font-medium">{{ $patient->address }}</p>
                            </td>

                            <td class="relative px-7 2xl:px-0">
                                <div class="space-x-2">
                                    <a href="{{ route('dashboard.patients.edit', ['id' => $patient->id]) }}">
                                        <button
                                            class="cursor-pointer rounded border border-blue-500 px-2 py-1 text-sm text-blue-500 transition-colors hover:bg-blue-500 hover:text-white md:text-base"
                                        >
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </a>

                                    <a href="{{ route('dashboard.patients.details', ['id' => $patient->id]) }}">
                                        <button
                                            class="cursor-pointer rounded border border-blue-500 px-2 py-1 text-sm text-blue-500 transition-colors hover:bg-blue-500 hover:text-white md:text-base"
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('dashboard.patients.delete', ['id' => $patient->id]) }}"
                                        style="display: inline"
                                        onsubmit="return confirm('Are you sure you want to delete this item?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="cursor-pointer rounded border border-red-500 px-2 py-1 text-sm text-red-500 transition-colors hover:bg-red-500 hover:text-white md:text-base"
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('dashboard.patients.prescriptions', ['id' => $patient->id]) }}">
                                        <button
                                            class="cursor-pointer rounded border border-green-500 px-2 py-1 text-sm text-green-500 transition-colors hover:bg-green-500 hover:text-white md:text-base"
                                        >
                                            <i class="fa-solid fa-file-prescription"></i>
                                        </button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :data="[
            'links' => $patients->toArray()['links'],
        ]" />
    </div>
@endsection
