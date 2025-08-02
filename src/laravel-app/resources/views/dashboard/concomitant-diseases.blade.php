@extends('dashboard-layout')

@section('content')
    <div class="h-full w-full">
        <div class="rounded-tl-lg rounded-tr-lg bg-gray-100 px-4 py-4 md:px-10 md:py-7">
            <div class="items-center justify-center sm:flex">
                <p
                    tabindex="0"
                    class="text-base leading-normal font-bold text-gray-800 focus:outline-none sm:text-lg md:text-xl lg:text-2xl"
                >
                    Diseases Management
                </p>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-5 md:mt-10 md:grid-cols-2 md:gap-20">
                <form
                    method="POST"
                    class="flex gap-1 md:gap-5"
                    action="{{ route('dashboard.concomitant-diseases.store') }}"
                >
                    @csrf
                    <div class="mx-auto flex w-full">
                        <div class="w-full">
                            <label class="relative block w-full cursor-text">
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    placeholder="Disease Name"
                                    class="peer block w-full rounded border border-gray-300 bg-white px-4 py-3 placeholder-transparent shadow-sm transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                />

                                <span
                                    class="absolute top-3 left-4 cursor-text bg-white px-1 text-sm text-gray-400 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-focus:top-[-0.6rem] peer-focus:text-xs peer-focus:text-blue-500"
                                    onclick="document.getElementById('name').focus()"
                                >
                                    Disease Name
                                </span>
                            </label>
                        </div>
                    </div>

                    <select
                        id="type"
                        name="category"
                        class="peer inline w-full rounded border border-gray-300 bg-white px-4 py-3 placeholder-transparent shadow-sm transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        required
                    >
                        <option value="" disabled selected>Select category</option>
                        <option value="medical">Medical</option>
                        <option value="surgical">Surgical</option>
                        <option value="gynae & obst">Gynae & obst</option>
                    </select>

                    <x-button :data="[
                        'content' => 'Create New',
                    ]" />
                </form>

                <x-search
                    :data="[
                        'route' => 'dashboard.concomitant-diseases',
                        'search' => $search,
                    ]"
                />
            </div>
        </div>

        <x-table
            :data="[
                'columns' => [
                    'Disease Name',
                    'Category',
                    'Created At',
                ],
                'rows' => $diseases,
                'table_fields' => [
                    'diseases' => [
                        'name',
                        'text'
                    ],
                    'type' => [
                        'category',
                        'select',
                        [
                            'medical',
                            'surgical',
                            'gynae & obst'
                        ]
                    ],
                    'created_at' => ['created_at'],
                ],
                'route' => 'dashboard.concomitant-diseases',
            ]"
        />

        <x-pagination :data="[
            'links' => $diseases->toArray()['links'],
        ]" />
    </div>
@endsection
