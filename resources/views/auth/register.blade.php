
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | SIMS</title>
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
        <p>Create an account to continue.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label for="name">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus>
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="password">Password</label>
            <input id="password" type="password" name="password" autocomplete="new-password" required>
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>

            <button type="submit">Create account</button>
        </form>

        <p>Already have an account? <a href="{{ route('login') }}">Log in</a></p>
    </main>
</body>
</html>
