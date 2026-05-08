@php
    $toastIcon = '';
    $toastTitle = '';
    $toastMsg = '';
    $iconBg = '#fef2f2';
    $iconColor = '#ef4444';

    if (session('success')) {
        $toastIcon = 'check-circle';
        $toastTitle = '¡Éxito!';
        $toastMsg = session('success');
        $iconBg = '#f0fdf4';
        $iconColor = '#16a34a';
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
    <div id="toast-message" class="fixed top-6 right-6 z-[100]"
        style="background:white;border:1px solid #f1f5f9;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);min-width:320px;padding:1rem 1.25rem;animation:toast-slide-up 0.3s cubic-bezier(0.16,1,0.3,1) forwards;">
        <div style="display:grid;grid-template-columns:4px 1fr 24px;align-items:center;">
            <div></div>
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:50%;background:{{ $iconBg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="{{ $toastIcon }}" style="width:16px;height:16px;color:{{ $iconColor }};"></i>
                </div>
                <span style="font-weight:700;font-size:15px;color:#1e293b;">{{ $toastTitle }}</span>
            </div>
            <button onclick="document.getElementById('toast-message').remove()"
                style="background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;display:flex;align-items:center;justify-content:center;"
                onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">
                <i data-lucide="x" style="width:16px;height:16px;"></i>
            </button>
        </div>
        <p style="text-align:center;font-size:13.5px;color:#64748b;margin:0.3rem 0 0;line-height:1.4;">
            {!! $toastMsg !!}</p>
    </div>
    <script>setTimeout(() => document.getElementById('toast-message')?.remove(), {{ (isset($errors) && $errors->any()) ? 6000 : 4000 }});</script>
@endif
