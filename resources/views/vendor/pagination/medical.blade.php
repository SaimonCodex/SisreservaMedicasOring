@if ($paginator->hasPages())
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-600">
            Mostrando <span class="font-semibold">{{ $paginator->firstItem() }}</span> a <span class="font-semibold">{{ $paginator->lastItem() }}</span> de <span class="font-semibold">{{ $paginator->total() }}</span> resultados
        </p>
        <div class="flex gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="btn btn-sm btn-outline opacity-50 cursor-not-allowed" disabled>
                    <i class="bi bi-chevron-left"></i>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm btn-outline">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <button class="btn btn-sm btn-outline" disabled>{{ $element }}</button>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="btn btn-sm bg-medical-600 text-white hover:bg-medical-700 border-medical-600">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="btn btn-sm btn-outline hover:bg-gray-50">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm btn-outline">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <button class="btn btn-sm btn-outline opacity-50 cursor-not-allowed" disabled>
                    <i class="bi bi-chevron-right"></i>
                </button>
            @endif
        </div>
    </div>
@endif
