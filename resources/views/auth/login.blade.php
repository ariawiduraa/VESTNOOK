<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - VESTNOOK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #202124; }
        .field-error { display: none; font-size: 12px; color: #dc2626; margin-top: 4px; }
        .field-error.show { display: block; }
        .input-err { border-color: #dc2626 !important; }
        .eye-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af; padding: 2px; display: flex; align-items: center; }
        .eye-btn:hover { color: #6b7280; }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/logovestnook.png') }}">
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-6 selection:bg-[#1a73e8] selection:text-white">

    <div class="w-full max-w-sm">
        
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-2">
                <span class="font-semibold text-xl tracking-tight">VESTNOOK</span>
            </div>
            <h1 class="text-2xl font-semibold text-gray-900 mt-6">Selamat Datang</h1>
            <p class="text-gray-500 text-sm mt-2">Masuk untuk melanjutkan ke dasbor Anda.</p>
        </div>

        {{-- Server-side errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-200 text-red-600 text-sm flex items-start gap-2">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
            @csrf
            
            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus
                    class="w-full px-4 py-2.5 rounded-md border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]/50 focus:border-[#1a73e8] transition-colors"
                    placeholder="nama@email.com">
                <p class="field-error" id="err-email">Email tidak boleh kosong.</p>
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" autocomplete="current-password"
                        class="w-full px-4 py-2.5 pr-11 rounded-md border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]/50 focus:border-[#1a73e8] transition-colors"
                        placeholder="••••••••">
                    <button type="button" class="eye-btn" onclick="toggleEye('password', this)" tabindex="-1" aria-label="Tampilkan password">
                        <svg id="eye-icon-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <p class="field-error" id="err-password">Password tidak boleh kosong.</p>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 bg-[#1a73e8] hover:bg-blue-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1a73e8]">
                Masuk
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-gray-500">
            Belum punya akun? <a href="{{ route('register') }}" class="text-[#1a73e8] font-medium hover:underline">Daftar sekarang</a>
        </p>
    </div>

    <script>
        function toggleEye(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon  = document.getElementById('eye-icon-' + fieldId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            icon.innerHTML = isHidden
                ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
                : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        }

        function showErr(id, msg) {
            const el = document.getElementById(id);
            el.textContent = msg;
            el.classList.add('show');
            document.getElementById(id.replace('err-','')).classList.add('input-err');
        }
        function clearErr(id) {
            document.getElementById(id).classList.remove('show');
            document.getElementById(id.replace('err-','')).classList.remove('input-err');
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let valid = true;
            const email    = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            clearErr('err-email'); clearErr('err-password');

            if (!email) {
                showErr('err-email', 'Email tidak boleh kosong.');
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showErr('err-email', 'Format email tidak valid.');
                valid = false;
            }

            if (!password) {
                showErr('err-password', 'Password tidak boleh kosong.');
                valid = false;
            }

            if (!valid) e.preventDefault();
        });

        // Clear error on input
        ['email','password'].forEach(id => {
            document.getElementById(id).addEventListener('input', () => clearErr('err-' + id));
        });
    </script>

</body>
</html>
