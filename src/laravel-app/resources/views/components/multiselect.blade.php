@props([
    'label',
    'inputName',
    'options',
    'optionKey' => 'name',
    'selected' => [],
])

<div
    class="multiselect-container relative"
    id="{{ \Illuminate\Support\Str::slug($inputName) }}-multiselect"
    data-selected='@json($selected)'
>
    <label class="mb-2 block text-sm font-medium text-gray-700">{{ $label }}</label>

    <div
        class="multiselect-selected mb-2 flex min-h-[2rem] flex-wrap gap-2 rounded border border-gray-300 bg-white px-2 py-1"
    ></div>

    <input
        type="text"
        class="multiselect-search block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
        placeholder="Search or select..."
        autocomplete="off"
    />

    <div
        class="multiselect-dropdown absolute z-10 mt-1 hidden max-h-40 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow"
    ></div>

    <div class="multiselect-hidden-inputs"></div>
</div>
