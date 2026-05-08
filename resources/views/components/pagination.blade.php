@props(['paginator'])

<nav class="flex items-center bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm" aria-label="Pagination">
    {{-- Botón Anterior --}}
    @if ($paginator->onFirstPage())
        <span class="px-3 py-2 text-slate-300 cursor-not-allowed"><i data-lucide="chevron-left" class="w-4 h-4"></i></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-slate-600 hover:bg-slate-50 transition-colors"><i data-lucide="chevron-left" class="w-4 h-4"></i></a>
    @endif

    {{-- Números de Página --}}
    @php
        $start = max(1, $paginator->currentPage() - 1);
        $end = min($paginator->lastPage(), $paginator->currentPage() + 1);
    @endphp

    @for ($i = $start; $i <= $end; $i++)
        @if ($i == $paginator->currentPage())
            <span class="px-4 py-2 border-x border-gray-100 text-sm font-bold text-blue-600 bg-blue-50/50">
                {{ $i }}
            </span>
        @else
            <a href="{{ $paginator->url($i) }}" class="px-4 py-2 border-x border-gray-100 text-sm font-bold text-slate-500 hover:bg-slate-50 transition-colors">
                {{ $i }}
            </a>
        @endif
    @endfor

    {{-- Botón Siguiente --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-slate-600 hover:bg-slate-50 transition-colors"><i data-lucide="chevron-right" class="w-4 h-4"></i></a>
    @else
        <span class="px-3 py-2 text-slate-300 cursor-not-allowed"><i data-lucide="chevron-right" class="w-4 h-4"></i></span>
    @endif
</nav>
