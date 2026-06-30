<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - VESTNOOK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #202124; }
        .field-error { display: none; font-size: 12px; color: #dc2626; margin-top: 4px; }
        .field-error.show { display: block; }
        .input-err { border-color: #dc2626 !important; }
        .eye-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af; padding: 2px; display: flex; align-items: center; }
        .eye-btn:hover { color: #6b7280; }

        /* Password strength bar */
        .strength-bar { height: 4px; border-radius: 2px; transition: width 0.3s, background-color 0.3s; width: 0%; }
        .strength-track { height: 4px; background: #e5e7eb; border-radius: 2px; margin-top: 8px; overflow: hidden; }
        .strength-label { font-size: 11px; margin-top: 4px; }

        /* Rule checklist */
        .rule { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #9ca3af; margin-top: 3px; transition: color 0.2s; }
        .rule.ok { color: #16a34a; }
        .rule svg { width: 12px; height: 12px; flex-shrink: 0; }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/logovestnook.png') }}">
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-6 selection:bg-[#1a73e8] selection:text-white">

    <div class="w-full max-w-sm py-8">
        
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-2">
                <span class="font-semibold text-xl tracking-tight">VESTNOOK</span>
            </div>
            <h1 class="text-2xl font-semibold text-gray-900 mt-6">Buat Akun</h1>
            <p class="text-gray-500 text-sm mt-2">Daftar untuk memulai analisis lahan.</p>
        </div>

        {{-- Server-side errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-200 text-red-600 text-sm flex items-start gap-2">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
            @csrf

            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" autocomplete="name" autofocus
                    class="w-full px-4 py-2.5 rounded-md border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]/50 focus:border-[#1a73e8] transition-colors"
                    placeholder="Nama Anda">
                <p class="field-error" id="err-name">Nama lengkap tidak boleh kosong.</p>
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email"
                    class="w-full px-4 py-2.5 rounded-md border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]/50 focus:border-[#1a73e8] transition-colors"
                    placeholder="nama@email.com">
                <p class="field-error" id="err-email">Email tidak boleh kosong.</p>
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" autocomplete="new-password"
                        class="w-full px-4 py-2.5 pr-11 rounded-md border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]/50 focus:border-[#1a73e8] transition-colors"
                        placeholder="Minimal 8 karakter">
                    <button type="button" class="eye-btn" onclick="toggleEye('password', this)" tabindex="-1" aria-label="Tampilkan password">
                        <svg id="eye-icon-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>

                {{-- Strength bar --}}
                <div class="strength-track" id="strength-track" style="display:none;">
                    <div class="strength-bar" id="strength-bar"></div>
                </div>
                <p class="strength-label" id="strength-label" style="display:none;"></p>

                {{-- Rules checklist --}}
                <div id="pw-rules" style="display:none; margin-top:6px;">
                    <div class="rule" id="rule-len">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Minimal 8 karakter
                    </div>
                    <div class="rule" id="rule-upper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Minimal 1 huruf besar (A–Z)
                    </div>
                    <div class="rule" id="rule-num">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Minimal 1 angka (0–9)
                    </div>
                    <div class="rule" id="rule-sym">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Minimal 1 simbol (!@#$%^&*)
                    </div>
                </div>

                <p class="field-error" id="err-password">Password tidak boleh kosong.</p>
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                        class="w-full px-4 py-2.5 pr-11 rounded-md border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]/50 focus:border-[#1a73e8] transition-colors"
                        placeholder="Ketik ulang password">
                    <button type="button" class="eye-btn" onclick="toggleEye('password_confirmation', this)" tabindex="-1" aria-label="Tampilkan konfirmasi">
                        <svg id="eye-icon-password_confirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <p class="field-error" id="err-password_confirmation">Konfirmasi password tidak boleh kosong.</p>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 mt-2 bg-[#1a73e8] hover:bg-blue-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1a73e8]">
                Daftar
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-gray-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-[#1a73e8] font-medium hover:underline">Masuk di sini</a>
        </p>
    </div>

    <script>
        /* ── Eye toggle ── */
        const EYE_OPEN  = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        const EYE_CLOSE = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;

        function toggleEye(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon  = document.getElementById('eye-icon-' + fieldId);
            const show  = input.type === 'password';
            input.type  = show ? 'text' : 'password';
            btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            icon.innerHTML = show ? EYE_CLOSE : EYE_OPEN;
        }

        /* ── Error helpers ── */
        function showErr(id, msg) {
            const el = document.getElementById('err-' + id);
            el.textContent = msg;
            el.classList.add('show');
            document.getElementById(id).classList.add('input-err');
        }
        function clearErr(id) {
            document.getElementById('err-' + id).classList.remove('show');
            document.getElementById(id).classList.remove('input-err');
        }

        /* ── Password rules ── */
        const RULES = {
            len:   v => v.length >= 8,
            upper: v => /[A-Z]/.test(v),
            num:   v => /[0-9]/.test(v),
            sym:   v => /[^A-Za-z0-9]/.test(v),
        };
        const STRENGTH_LEVELS = [
            { label: '', color: '' },
            { label: 'Lemah', color: '#ef4444' },
            { label: 'Sedang', color: '#f59e0b' },
            { label: 'Kuat', color: '#3b82f6' },
            { label: 'Sangat Kuat', color: '#16a34a' },
        ];

        function updateStrength(val) {
            const track  = document.getElementById('strength-track');
            const bar    = document.getElementById('strength-bar');
            const lbl    = document.getElementById('strength-label');
            const rules  = document.getElementById('pw-rules');

            if (!val) { track.style.display='none'; lbl.style.display='none'; rules.style.display='none'; return; }
            track.style.display='block'; lbl.style.display='block'; rules.style.display='block';

            let score = 0;
            Object.entries(RULES).forEach(([key, fn]) => {
                const el = document.getElementById('rule-' + key);
                const ok = fn(val);
                if (ok) { el.classList.add('ok'); score++; }
                else    { el.classList.remove('ok'); }
            });

            const lvl = STRENGTH_LEVELS[score] || STRENGTH_LEVELS[0];
            bar.style.width = (score * 25) + '%';
            bar.style.backgroundColor = lvl.color;
            lbl.textContent = lvl.label;
            lbl.style.color = lvl.color;
        }

        document.getElementById('password').addEventListener('input', function() {
            updateStrength(this.value);
            clearErr('password');
        });

        /* ── Form validation ── */
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            let valid = true;
            const name  = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const pw    = document.getElementById('password').value;
            const pwc   = document.getElementById('password_confirmation').value;

            clearErr('name'); clearErr('email'); clearErr('password'); clearErr('password_confirmation');

            if (!name) {
                showErr('name', 'Nama lengkap tidak boleh kosong.');
                valid = false;
            }
            if (!email) {
                showErr('email', 'Email tidak boleh kosong.');
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showErr('email', 'Format email tidak valid.');
                valid = false;
            }
            if (!pw) {
                showErr('password', 'Password tidak boleh kosong.');
                valid = false;
            } else {
                const failed = [];
                if (!RULES.len(pw))   failed.push('minimal 8 karakter');
                if (!RULES.upper(pw)) failed.push('1 huruf besar');
                if (!RULES.num(pw))   failed.push('1 angka');
                if (!RULES.sym(pw))   failed.push('1 simbol');
                if (failed.length) {
                    showErr('password', 'Password harus mengandung: ' + failed.join(', ') + '.');
                    valid = false;
                }
            }
            if (!pwc) {
                showErr('password_confirmation', 'Konfirmasi password tidak boleh kosong.');
                valid = false;
            } else if (pw && pwc !== pw) {
                showErr('password_confirmation', 'Konfirmasi password tidak cocok.');
                valid = false;
            }

            if (!valid) e.preventDefault();
        });

        /* Clear errors on type */
        ['name','email','password_confirmation'].forEach(id => {
            document.getElementById(id).addEventListener('input', () => clearErr(id));
        });
    </script>

</body>
</html>
