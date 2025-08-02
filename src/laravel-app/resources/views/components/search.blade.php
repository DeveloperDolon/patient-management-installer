<form
    action="{{ route($data['route'], $data['route_params'] ?? []) }}"

    method="GET"
>
    <div class="flex h-full w-full items-center gap-5 bg-white">
        <div class="relative w-full">
            <div class="absolute inset-0 m-auto ml-4 h-4 w-4 text-gray-600">
                <img
                    src="https://tuk-cdn.s3.amazonaws.com/can-uploader/light_with_header_and_icons-svg3.svg"
                    alt="Search"
                />
            </div>

            <input
                type="text"
                name="search"
                placeholder="Search"
                value="{{ $data['search'] }}"
                class="peer block w-full rounded border border-gray-300 bg-white px-4 py-3 pl-12 shadow-sm transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
        </div>

        <x-button :data="[
            'content' => 'Search',
        ]" />
    </div>
</form>
