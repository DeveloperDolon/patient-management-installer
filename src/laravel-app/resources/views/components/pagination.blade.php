@php
    $links = $data['links'] ?? [];
@endphp

<div>
    <nav class="pt-5">
        <ul class="flex justify-end items-center -space-x-px">
            @foreach ($links as $link)
                <li>
                    @if ($link['url'])
                        <a 
                            href="{{ $link['url'] }}" 
                            class="px-3 py-2 leading-tight {{ $link['active'] ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }} border border-gray-300 hover:bg-blue-500"
                            {!! $link['active'] ? 'aria-current="page"' : '' !!}
                        >
                            {!! $link['label'] !!}
                        </a>
                    @else
                        <span class="px-3 py-2 leading-tight text-gray-400 border border-gray-300 cursor-not-allowed">
                            {!! $link['label'] !!}
                        </span>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
</div>
