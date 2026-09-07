<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.sign_in') }} | Sainteku</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/uin.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f8fafc; color: #1e293b; font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.5; }
        a { color: inherit; text-underline-offset: 3px; }
        a:hover { text-decoration-thickness: 2px; }
        button, input { font: inherit; }
        button, a, input { touch-action: manipulation; }
        :focus-visible { outline: 3px solid #856B2B; outline-offset: 3px; }
        [hidden] { display: none !important; }
        .language { display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 1rem; padding: 1rem 1.5rem; font-size: .875rem; }
        .language a { padding: .5rem; }
        .language [aria-current] { font-weight: 700; }
        main { min-height: calc(100svh - 88px); display: grid; place-items: center; padding: 1rem 1.5rem 3rem; }
        .login-shell { width: 100%; max-width: 900px; display: grid; grid-template-columns: 5fr 7fr; background: white; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden; }
        .brand { display: flex; flex-direction: column; justify-content: center; text-align: center; padding: 2.5rem 2rem; background: #FEEB04; color: #1c1917; }
        .brand img { width: 80px; height: auto; margin: 0 auto 1rem; }
        .brand h2 { font-size: 2rem; margin: 0 0 .5rem; }
        .brand p { font-size: .875rem; margin: .25rem 0; }
        .forms { min-width: 0; padding: 2.5rem; }
        h1, h2 { line-height: 1.25; }
        .forms h1, .forms h2 { font-size: 1.5rem; margin: 0 0 .5rem; }
        .description { color: #475569; font-size: .875rem; margin: 0 0 1.75rem; }
        .field { margin-bottom: 1.25rem; }
        .field label { display: block; font-size: .875rem; font-weight: 600; margin-bottom: .5rem; }
        .field input { width: 100%; min-height: 46px; border: 1px solid #94a3b8; border-radius: .5rem; padding: .625rem .75rem; background: white; color: #1e293b; }
        input::placeholder { color: #64748b; }
        .password { display: flex; align-items: center; gap: .5rem; }
        .password input { min-width: 0; }
        button { cursor: pointer; }
        .text-button { border: 0; background: transparent; color: #334155; min-height: 44px; padding: .5rem; text-decoration: underline; text-underline-offset: 3px; font-size: .875rem; }
        .options { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; font-size: .875rem; }
        .remember { display: flex; align-items: center; gap: .5rem; min-height: 44px; }
        .remember input { width: 18px; height: 18px; accent-color: #856B2B; }
        .submit { width: 100%; min-height: 46px; padding: .75rem; border: 0; border-radius: .5rem; background: #FEEB04; color: #1c1917; font-weight: 700; }
        .submit:hover { background: #CBB800; }
        .submit:disabled { opacity: .65; cursor: wait; }
        .alert { padding: .75rem 1rem; margin-bottom: 1.25rem; border-radius: .5rem; font-size: .875rem; overflow-wrap: anywhere; background: #fef2f2; color: #991b1b; }
        .alert.success { background: #f0fdf4; color: #166534; }
        @media (max-width: 640px) {
            main { padding: .5rem 1rem 2rem; }
            .login-shell { grid-template-columns: 1fr; }
            .brand { padding: 1.5rem; }
            .brand img { width: 56px; margin-bottom: .5rem; }
            .brand h2 { font-size: 1.5rem; }
            .forms { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <nav class="language" aria-label="{{ __('messages.language') }}">
        <a href="{{ route('language.switch', 'id') }}" lang="id" @if(app()->getLocale() === 'id') aria-current="true" @endif>Indonesia</a>
        <a href="{{ route('language.switch', 'en') }}" lang="en" @if(app()->getLocale() === 'en') aria-current="true" @endif>English</a>
    </nav>
    <main>
        <div class="login-shell">
            <section class="brand" aria-label="Sainteku">
                <img src="{{ asset('assets/images/uin.png') }}" alt="Logo UIN Saizu">
                <h2>Sainteku</h2>
                <p>{{ __('messages.faculty_name') }}</p>
                <p>UIN Prof. K.H. Saifuddin Zuhri Purwokerto</p>
            </section>
            <div class="forms">
                @if(session('status') || session('success'))
                    <div class="alert success" role="status">{{ session('status') ?: session('success') }}</div>
                @endif
                @if(session('error') || $errors->any())
                    <div class="alert" role="alert">{{ session('error') ?: $errors->first() }}</div>
                @endif
                <section id="loginPanel" aria-labelledby="loginTitle">
                    <h1 id="loginTitle" tabindex="-1">{{ __('messages.sign_in') }}</h1>
                    <p class="description">{{ __('messages.enter_credentials') }}</p>
                    <div id="loginFormAlert" class="alert" role="alert" hidden></div>
                    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                        @csrf
                        <div class="field">
                            <label for="credential">{{ __('messages.forgot_password_credential_label') }}</label>
                            <input type="text" id="credential" name="credential" value="{{ old('credential') }}" autocomplete="username" autocapitalize="none" spellcheck="false" required>
                        </div>
                        <div class="field">
                            <label for="password">{{ __('messages.password_label') }}</label>
                            <div class="password">
                                <input type="password" id="password" name="password" autocomplete="current-password" required>
                                <button type="button" id="togglePasswordBtn" class="text-button" aria-controls="password" aria-pressed="false">{{ __('messages.show_password') }}</button>
                            </div>
                        </div>
                        <div class="options">
                            <label class="remember" for="remember"><input type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))>{{ __('messages.remember_me') }}</label>
                            <a href="{{ route('password.request') }}" id="forgotPasswordLink">{{ __('messages.forgot_password') }}</a>
                        </div>
                        <button type="submit" class="submit">{{ __('messages.login') }}</button>
                    </form>
                </section>
                <section id="forgotPasswordPanel" aria-labelledby="forgotTitle" hidden>
                    <h2 id="forgotTitle" tabindex="-1">{{ __('messages.forgot_password_title') }}</h2>
                    <p class="description">{{ __('messages.forgot_password_desc') }}</p>
                    <div id="forgotPasswordAlert" class="alert" role="alert" hidden></div>
                    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                        @csrf
                        <div class="field">
                            <label for="forgot_credential">{{ __('messages.forgot_password_credential_label') }}</label>
                            <input type="text" id="forgot_credential" name="credential" autocomplete="username" autocapitalize="none" spellcheck="false" required>
                        </div>
                        <button type="submit" class="submit">{{ __('messages.send_reset_link') }}</button>
                        <button type="button" id="backToLogin" class="text-button">{{ __('messages.back_to_login') }}</button>
                    </form>
                </section>
            </div>
        </div>
    </main>
    <script>
        const loginPanel = document.getElementById('loginPanel');
        const forgotPanel = document.getElementById('forgotPasswordPanel');
        document.getElementById('forgotPasswordLink').addEventListener('click', event => {
            event.preventDefault();
            loginPanel.hidden = true;
            forgotPanel.hidden = false;
            document.getElementById('forgotTitle').focus();
        });
        document.getElementById('backToLogin').addEventListener('click', () => {
            forgotPanel.hidden = true;
            loginPanel.hidden = false;
            document.getElementById('loginTitle').focus();
        });
        document.getElementById('togglePasswordBtn').addEventListener('click', event => {
            const password = document.getElementById('password');
            const visible = password.type === 'password';
            password.type = visible ? 'text' : 'password';
            event.currentTarget.setAttribute('aria-pressed', String(visible));
        });
        for (const [formId, alertId] of [['loginForm', 'loginFormAlert'], ['forgotPasswordForm', 'forgotPasswordAlert']]) {
            const form = document.getElementById(formId);
            form.addEventListener('submit', async event => {
                event.preventDefault();
                const button = form.querySelector('[type="submit"]');
                if (button.disabled) return;
                const originalText = button.textContent;
                const alert = document.getElementById(alertId);
                let retryAfter = 0;
                let redirecting = false;
                button.disabled = true;
                button.textContent = @json(__('messages.processing'));
                form.setAttribute('aria-busy', 'true');
                alert.hidden = true;
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();
                    const success = response.ok && data.success;
                    alert.textContent = Object.values(data.errors || {}).flat().join(' ') || data.message || @json(__('messages.error_occurred'));
                    alert.className = success ? 'alert success' : 'alert';
                    alert.hidden = false;
                    if (success && formId === 'loginForm') {
                        window.location.assign(data.redirect || @json(url('/dashboard')));
                        redirecting = true;
                    } else if (success) {
                        form.reset();
                    }
                    if (response.status === 429) retryAfter = Math.max(1, Number(data.retry_after) || 60);
                } catch {
                    alert.textContent = @json(__('messages.error_occurred'));
                    alert.className = 'alert';
                    alert.hidden = false;
                } finally {
                    form.removeAttribute('aria-busy');
                    button.textContent = originalText;
                    if (!redirecting) {
                        if (retryAfter) setTimeout(() => { button.disabled = false; }, retryAfter * 1000);
                        else button.disabled = false;
                    }
                }
            });
        }
    </script>
</body>
</html>
