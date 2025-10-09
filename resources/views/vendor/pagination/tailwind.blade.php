@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-6">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 mx-1 rounded-md cursor-not-allowed
                         bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="px-4 py-2 mx-1 rounded-md transition font-medium
                      bg-gray-200 text-gray-700 hover:bg-gray-300
                      dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                Previous
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-4 py-2 mx-1 rounded-md
                             bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                              class="px-4 py-2 mx-1 rounded-md font-semibold shadow
                                     text-white bg-indigo-600 dark:bg-indigo-500 dark:text-white">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="px-4 py-2 mx-1 rounded-md transition font-medium
                                  bg-gray-200 text-gray-700 hover:bg-gray-300
                                  dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="px-4 py-2 mx-1 rounded-md transition font-medium
                      bg-gray-200 text-gray-700 hover:bg-gray-300
                      dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                Next
            </a>
        @else
            <span class="px-4 py-2 mx-1 rounded-md cursor-not-allowed
                         bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                Next
            </span>
        @endif
    </nav>
@endif
