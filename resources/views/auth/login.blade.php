<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SIMS</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6fb; color: #172033; margin: 0; min-height: 100vh; display: grid; place-items: center; }
        main { background: white; width: min(420px, calc(100% - 40px)); padding: 32px; border-radius: 12px; box-shadow: 0 12px 36px #17203314; box-sizing: border-box; }
        h1 { font-size: 1.5rem; margin: 0 0 8px; }
        p { color: #5b6475; line-height: 1.5; }
        label { display: block; font-weight: 600; margin: 18px 0 6px; }
        input { width: 100%; padding: 11px 12px; border: 1px solid #cbd2df; border-radius: 6px; box-sizing: border-box; font: inherit; }
        button { width: 100%; margin-top: 22px; padding: 12px; color: white; background: #3157c8; border: 0; border-radius: 6px; font: inherit; font-weight: 600; cursor: pointer; }
        .error { color: #b42318; margin: 6px 0 0; }
        a { color: #3157c8; }
    </style>
</head>
<body>
    <main>
        <h1>Student Information System</h1>
        <p>Sign in to continue to your dashboard.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="password">Password</label>
            <input id="password" type="password" name="password" autocomplete="current-password" required>
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror

            <button type="submit">Log in</button>
        </form>

        <p>Don't have an account? <a href="{{ route('register') }}">Create one</a></p>
    </main>
</body>
</html>