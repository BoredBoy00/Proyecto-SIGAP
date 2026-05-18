@php
    $toastIcon = '';
    $toastTitle = '';
    $toastMsg = '';
    $iconBgClass = 'bg-red-50 dark:bg-red-900/20';
    $iconColorClass = 'text-red-500 dark:text-red-400';

    if (session('success')) {
        $toastIcon = 'check-circle';
        $toastTitle = '¡Éxito!';
        $toastMsg = session('success');
        $iconBgClass = 'bg-green-50 dark:bg-green-900/20';
        $iconColorClass = 'text-green-500 dark:text-green-400';
    } elseif (session('deleted')) {
        $toastIcon = 'trash-2';
        $toastTitle = 'Eliminado';
        $toastMsg = session('deleted');
    } elseif (session('error')) {
        $toastIcon = 'alert-circle';
        $toastTitle = 'Error';
        $toastMsg = session('error');
    } elseif (isset($errors) && $errors->any()) {
        $toastIcon = 'alert-circle';
        $toastTitle = 'Error';
        $toastMsg = implode('<br>', $errors->all());
    }
@endphp

@if($toastMsg)
    <div id="toast-message" class="toast-fixed-center bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-2xl min-w-[320px] p-4">
        <div class="grid grid-cols-[4px_1fr_24px] items-center">
            <div></div>
            <div class="flex items-center justify-center gap-2.5">
                <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 {{ $iconBgClass }}">
                    <i data-lucide="{{ $toastIcon }}" class="w-4 h-4 {{ $iconColorClass }}"></i>
                </div>
                <span class="font-bold text-sm text-slate-800 dark:text-slate-100">{{ $toastTitle }}</span>
            </div>
            <button onclick="document.getElementById('toast-message').remove()"
                class="bg-none border-none cursor-pointer text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0 flex items-center justify-center transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
            {!! $toastMsg !!}</p>
    </div>
    <script>setTimeout(() => document.getElementById('toast-message')?.remove(), {{ (isset($errors) && $errors->any()) ? 6000 : 4000 }});</script>
@endif
