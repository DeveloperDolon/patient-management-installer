<div x-data="{ open: false }" class="inline">
    @if (in_array('isView', $data) && $data['isView'] === true)
        <button
            @click="open = true"
            class="inline rounded border border-blue-500 px-2 py-1 text-sm text-blue-500 transition-colors hover:bg-blue-500 hover:text-white md:text-base"
        >
            <i class="fa-solid fa-eye"></i>
        </button>
    @else
        <button
            @click="open = true"
            class="inline rounded border border-green-500 px-2 py-1 text-sm text-green-500 transition-colors hover:bg-green-500 hover:text-white md:text-base"
        >
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    @endif
    <div
        x-show="open"
        x-transition
        x-cloak
        class="relative z-10 inline"
        aria-labelledby="dialog-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <form
                    method="POST"
                    action="{{ route($data['update_route'], ['id' => $data['previous_data']->id]) }}"
                    class="relative transform overflow-hidden rounded-lg bg-gray-200 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                >
                    @csrf
                    <h2 class="pt-2 text-center text-base font-bold md:text-lg">{{in_array('isView', $data) && $data['isView'] === true ? "View" : "Edit"}}</h2>
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div>
                            @foreach ($data['input_fields'] as $field)
                                @if ($field[0] === 'created_at')
                                @else
                                    @if ($field[1] === 'text')
                                        <div class="mb-4">
                                            <label
                                                for="{{ $field[0] }}"
                                                class="mb-1 block text-sm font-medium text-gray-700"
                                            >
                                                {{ ucfirst(str_replace('_', ' ', $field[0])) }}
                                            </label>
                                            <textarea
                                                type="text"
                                                name="{{ $field[0] }}"
                                                id="{{ $field[0] }}"
                                                @if(in_array('isView', $data) && $data['isView'] === true) readonly @endif
                                                placeholder="Input {{ $field[0] }}"
                                                class="block w-full rounded-md border-gray-300 bg-white p-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            >{{ $data['previous_data'][$field[0]] }}</textarea>
                                        </div>
                                    @elseif ($field[1] === 'select')
                                        <div class="mb-4">
                                            <label
                                                for="{{ $field[0] }}"
                                                class="mb-1 block text-sm font-medium text-gray-700"
                                            >
                                                {{ ucfirst(str_replace('_', ' ', $field[0])) }}
                                            </label>

                                            <select class="w-full rounded-md bg-white p-2" name="{{ $field[0] }}">
                                                <option value="" disabled>Please select {{ $field[0] }}</option>
                                                @foreach ($field[2] as $option)
                                                    <option
                                                        class="capitalize"
                                                        @if($data['previous_data'][$field[0]] === $option) selected @endif
                                                        value="{{ $option }}"
                                                    >
                                                        {{ ucfirst(str_replace('_', ' ', $option)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-gray-200 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        @if (! in_array('isView', $data) || $data['isView'] == false)
                            <button
                                type="submit"
                                class="inline-flex w-full cursor-pointer justify-center rounded-md bg-violet-500 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 sm:ml-3 sm:w-auto"
                            >
                                Update
                            </button>
                        @endif

                        <button
                            type="button"
                            @click="open = false"
                            class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
