<div class="h-[calc(100vh-23rem)] overflow-y-auto w-full bg-white px-4 pt-4 pb-5 shadow md:px-10 md:pt-7">
    <table class="w-full whitespace-nowrap">
        <thead>
            <tr tabindex="0" class="h-16 w-full text-sm leading-none text-gray-800 focus:outline-none">
                @foreach ($data['columns'] as $column)
                    <th class="text-left font-bold">{{ $column }}</th>
                @endforeach

                <th class="px-4 text-left font-bold">Actions</th>
            </tr>
        </thead>
        <tbody class="w-full bg-amber-600">
            @foreach ($data['rows'] as $row)
                <tr
                    tabindex="0"
                    class="h-12 border-t border-b border-gray-100 bg-white text-sm leading-none text-gray-800 hover:bg-gray-100 focus:outline-none"
                >
                    @foreach ($data['table_fields'] as $field_name)
                        <td class="">
                            <p class="font-medium">
                                {{ ucfirst(str_replace('_', ' ', substr($row[$field_name[0]], 0, 30))) }}
                                {{strlen($row[$field_name[0]]) > 30 ? '...' : ''}}
                            </p>
                        </td>
                    @endforeach

                    <td class="relative px-7 2xl:px-0">
                        <div class="space-x-2">
                            <x-modal
                                :data="[
                                    'input_fields' => $data['table_fields'],
                                    'previous_data' => $row,
                                    'update_route' => ($data['route'] . '.update'),
                                    'isView' => true
                                ]"
                            />

                            <x-modal
                                :data="[
                                    'input_fields' => $data['table_fields'],
                                    'previous_data' => $row,
                                    'update_route' => ($data['route'] . '.update')
                                ]"
                            />

                            <form
                                method="POST"
                                action="{{ route($data['route'] . '.delete', $row['id']) }}"
                                style="display: inline"
                                onsubmit="return confirm('Are you sure you want to delete this item?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="rounded border border-red-500 px-2 py-1 text-sm text-red-500 transition-colors hover:bg-red-500 hover:text-white md:text-base"
                                    class="cursor-pointer rounded-lg bg-red-500 text-white"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
