@props([
    'image',
    'alt' => '',
    'class' => '',
    'deleteRoute' => null,
])

<div x-data="{ open: false }" class="p-2">
    <div class="group relative w-fit">
        <img
            @click="open = true"
            src="{{ $image }}"
            alt="{{ $alt }}"
            class="{{ $class }} h-32 w-48 cursor-zoom-in rounded object-cover shadow-lg"
        />

        @if ($deleteRoute)
            <button
                type="button"
                class="absolute top-1 right-1 hidden rounded-full bg-red-600 px-2 py-1 text-xs text-white shadow group-hover:block hover:bg-red-700"
                title="Delete"
                x-on:click.prevent="
                    if (confirm('Are you sure you want to delete this image?')) {
                        fetch('{{ $deleteRoute }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ image: '{{ $image }}', _method: 'DELETE' }),
                        })
                            .then((response) => {
                                if (response.ok) {
                                    $el.closest('.group').remove()
                                } else {
                                    response
                                        .json()
                                        .then((data) => {
                                            alert(data.message || 'Failed to delete image.')
                                        })
                                        .catch(() => alert('Failed to delete image.'))
                                }
                            })
                            .catch(() => alert('Failed to delete image.'))
                    }
                "
            >
                &times;
            </button>
        @endif
    </div>

    <div
        x-show="open"
        x-transition
        class="bg-opacity-70 fixed inset-0 z-50 flex items-center justify-center bg-black"
        @click.self="open = false"
    >
        <div class="relative max-w-3xl p-4">
            <button @click="open = false" class="absolute top-2 right-2 text-2xl font-bold text-white">&times;</button>

            <img
                src="{{ $image }}"
                alt="{{ $alt }}"
                class="max-h-[90vh] w-full cursor-zoom-out object-contain transition-transform duration-300 hover:scale-110"
                @click="open = false"
            />
        </div>
    </div>
</div>
