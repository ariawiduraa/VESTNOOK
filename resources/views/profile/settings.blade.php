<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Profil – VESTNOOK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .field-error { font-size:12px; color:#dc2626; margin-top:4px; display:none; }
        .field-error.show { display:block; }
        .input-err { border-color:#dc2626 !important; }
        .eye-btn { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:2px; display:flex; align-items:center; }
        .eye-btn:hover { color:#6b7280; }
        .section-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:28px; }
        .avatar-ring { width:96px; height:96px; border-radius:50%; object-fit:cover; border:3px solid #e2e8f0; }
        .strength-track { height:4px; background:#e5e7eb; border-radius:2px; margin-top:8px; overflow:hidden; }
        .strength-bar { height:4px; border-radius:2px; transition:width .3s, background-color .3s; width:0%; }
        .rule { display:flex; align-items:center; gap:5px; font-size:11px; color:#9ca3af; margin-top:3px; transition:color .2s; }
        .rule.ok { color:#16a34a; }
        .rule svg { width:12px; height:12px; flex-shrink:0; }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/logovestnook.png') }}">
</head>
<body class="antialiased min-h-screen bg-slate-50 text-slate-800">

    {{-- Navbar --}}
    <nav class="w-full h-16 px-6 flex items-center justify-between bg-white border-b border-slate-200/80 sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logovestnook.png') }}" alt="VESTNOOK" style="width:28px;height:28px;object-fit:contain;">
            <a href="/" class="font-bold text-lg text-blue-600 tracking-tight">VESTNOOK</a>
        </div>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </nav>

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-10 space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Profil</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola informasi akun, foto profil, dan keamanan password Anda.</p>
        </div>

        {{-- Flash success --}}
        @if(session('success'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Server errors --}}
        @if($errors->any())
            <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6" novalidate>
            @csrf

            {{-- ─── Foto Profil ─── --}}
            <div class="section-card">
                <h2 class="text-base font-semibold text-slate-800 mb-5">Foto Profil</h2>
                <div class="flex items-center gap-5">
                    <img id="avatar-preview" src="{{ $user->avatarUrl() }}" alt="avatar" class="avatar-ring">
                    <div>
                        <label for="avatar" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-full cursor-pointer transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Ganti Foto
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="previewAvatar(this)">
                        <p class="text-xs text-slate-400 mt-2">JPG, PNG, atau WebP. Maks. 2MB.</p>
                        <p class="field-error" id="err-avatar"></p>
                    </div>
                </div>
            </div>

            {{-- ─── Informasi Akun ─── --}}
            <div class="section-card space-y-4">
                <h2 class="text-base font-semibold text-slate-800">Informasi Akun</h2>

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Pengguna</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors text-sm"
                        placeholder="Nama Anda">
                    <p class="field-error" id="err-name">Nama tidak boleh kosong.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="text" value="{{ $user->email }}" disabled
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-50 text-slate-400 text-sm cursor-not-allowed">
                    <p class="text-xs text-slate-400 mt-1">Email tidak dapat diubah.</p>
                </div>

                <div class="pt-1">
                    <button type="submit" name="action" value="profile"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/40">
                        Simpan Perubahan
                    </button>
                </div>
            </div>

            {{-- ─── Ganti Password ─── --}}
            <div class="section-card space-y-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Ganti Password</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kosongkan jika tidak ingin mengganti password.</p>
                </div>

                {{-- Password saat ini --}}
                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Password Saat Ini</label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                            class="w-full px-4 py-2.5 pr-11 rounded-xl border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors text-sm"
                            placeholder="••••••••">
                        <button type="button" class="eye-btn" onclick="toggleEye('current_password',this)" tabindex="-1">
                            <svg id="eye-icon-current_password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password baru --}}
                <div>
                    <label for="new_password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="new_password" name="new_password" autocomplete="new-password"
                            class="w-full px-4 py-2.5 pr-11 rounded-xl border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors text-sm"
                            placeholder="Minimal 8 karakter">
                        <button type="button" class="eye-btn" onclick="toggleEye('new_password',this)" tabindex="-1">
                            <svg id="eye-icon-new_password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="strength-track mt-2" id="strength-track" style="display:none"><div class="strength-bar" id="strength-bar"></div></div>
                    <div id="pw-rules" style="display:none; margin-top:6px;">
                        <div class="rule" id="rule-len"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Minimal 8 karakter</div>
                        <div class="rule" id="rule-upper"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Minimal 1 huruf besar</div>
                        <div class="rule" id="rule-num"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Minimal 1 angka</div>
                        <div class="rule" id="rule-sym"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Minimal 1 simbol</div>
                    </div>
                    <p class="field-error" id="err-new_password"></p>
                </div>

                {{-- Konfirmasi password baru --}}
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password"
                            class="w-full px-4 py-2.5 pr-11 rounded-xl border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors text-sm"
                            placeholder="Ulangi password baru">
                        <button type="button" class="eye-btn" onclick="toggleEye('new_password_confirmation',this)" tabindex="-1">
                            <svg id="eye-icon-new_password_confirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="field-error" id="err-conf"></p>
                </div>

                <div class="pt-1">
                    <button type="submit" name="action" value="password"
                        class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-slate-500/40">
                        Ganti Password
                    </button>
                </div>
            </div>

        </form>
    </main>

    <script>
        /* ── Avatar preview ── */
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.size > 2 * 1024 * 1024) {
                    document.getElementById('err-avatar').textContent = 'Ukuran file maksimal 2MB.';
                    document.getElementById('err-avatar').classList.add('show');
                    input.value = '';
                    return;
                }
                document.getElementById('err-avatar').classList.remove('show');
                const reader = new FileReader();
                reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
                reader.readAsDataURL(file);
            }
        }

        /* ── Eye toggle ── */
        const EYE_OPEN  = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        const EYE_CLOSE = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
        function toggleEye(id, btn) {
            const input = document.getElementById(id);
            const icon  = document.getElementById('eye-icon-' + id);
            const show  = input.type === 'password';
            input.type  = show ? 'text' : 'password';
            icon.innerHTML = show ? EYE_CLOSE : EYE_OPEN;
        }

        /* ── Strength meter ── */
        const RULES = { len: v=>v.length>=8, upper: v=>/[A-Z]/.test(v), num: v=>/[0-9]/.test(v), sym: v=>/[^A-Za-z0-9]/.test(v) };
        const LVLS  = ['', '#ef4444', '#f59e0b', '#3b82f6', '#16a34a'];
        document.getElementById('new_password').addEventListener('input', function() {
            const v = this.value;
            const track = document.getElementById('strength-track');
            const bar   = document.getElementById('strength-bar');
            const rules = document.getElementById('pw-rules');
            if (!v) { track.style.display='none'; rules.style.display='none'; return; }
            track.style.display='block'; rules.style.display='block';
            let score = 0;
            Object.entries(RULES).forEach(([k, fn]) => {
                const el = document.getElementById('rule-'+k);
                fn(v) ? (el.classList.add('ok'), score++) : el.classList.remove('ok');
            });
            bar.style.width  = (score*25)+'%';
            bar.style.backgroundColor = LVLS[score] || '';
        });

        /* ── Form validation ── */
        function showErr(id, msg) { const e=document.getElementById(id); e.textContent=msg; e.classList.add('show'); }
        function clearErr(id)     { document.getElementById(id).classList.remove('show'); }

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const action = document.activeElement.value;
            clearErr('err-name'); clearErr('err-new_password'); clearErr('err-conf');

            if (action === 'password') {
                const cur  = document.getElementById('current_password').value;
                const npw  = document.getElementById('new_password').value;
                const conf = document.getElementById('new_password_confirmation').value;
                let valid  = true;

                if (!cur) { showErr('err-new_password', 'Isi password saat ini terlebih dahulu.'); valid=false; }
                if (npw) {
                    const failed = [];
                    if (!RULES.len(npw))   failed.push('8 karakter');
                    if (!RULES.upper(npw)) failed.push('huruf besar');
                    if (!RULES.num(npw))   failed.push('angka');
                    if (!RULES.sym(npw))   failed.push('simbol');
                    if (failed.length) { showErr('err-new_password', 'Password kurang: '+failed.join(', ')+'.'); valid=false; }
                }
                if (npw && conf !== npw) { showErr('err-conf', 'Konfirmasi tidak cocok.'); valid=false; }
                if (!valid) e.preventDefault();
            }
        });
    </script>
</body>
</html>
