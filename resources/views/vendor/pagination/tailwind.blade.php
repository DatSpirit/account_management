@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-6">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 mx-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 mx-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Previous</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-4 py-2 mx-1 bg-gray-200 text-gray-500 rounded-md">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="px-4 py-2 mx-1 text-white bg-indigo-600 rounded-md shadow">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 mx-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 mx-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Next</a>
        @else
            <span class="px-4 py-2 mx-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Next</span>
        @endif
    </nav>
@endif
