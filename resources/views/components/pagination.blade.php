@props(['paginator'])

<nav class="flex items-center bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm" aria-label="Pagination">
    {{-- Botón Anterior --}}
    @if ($paginator->onFirstPage())
        <span class="px-3 py-2 text-slate-300 dark:text-slate-700 cursor-not-allowed"><i data-lucide="chevron-left" class="w-4 h-4"></i></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"><i data-lucide="chevron-left" class="w-4 h-4"></i></a>
    @endif

    {{-- Números de Página --}}
    @php
        $start = max(1, $paginator->currentPage() - 1);
        $end = min($paginator->lastPage(), $paginator->currentPage() + 1);
    @endphp

    @for ($i = $start; $i <= $end; $i++)
        @if ($i == $paginator->currentPage())
            <span class="px-4 py-2 border-x border-gray-100 dark:border-slate-800 text-sm font-bold text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20">
                {{ $i }}
            </span>
        @else
            <a href="{{ $paginator->url($i) }}" class="px-4 py-2 border-x border-gray-100 dark:border-slate-800 text-sm font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                {{ $i }}
            </a>
        @endif
    @endfor

    {{-- Botón Siguiente --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"><i data-lucide="chevron-right" class="w-4 h-4"></i></a>
    @else
        <span class="px-3 py-2 text-slate-300 dark:text-slate-700 cursor-not-allowed"><i data-lucide="chevron-right" class="w-4 h-4"></i></span>
    @endif
</nav>
