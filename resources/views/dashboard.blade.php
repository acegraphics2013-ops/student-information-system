
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>Dashboard | SIMS</title>
</head>
<body>
    <h1>SIMS Dashboard</h1>

    <h2>Welcome, {{ auth()->user()->name }}!</h2>

    <p>
        Role: {{ ucfirst(auth()->user()->role) }}
    </p>

    <h3>System Modules</h3>

    <p>Student Management — Coming Soon</p>
    <p>Subject Management — Coming Soon</p>
    <p>Enrollment Management — Coming Soon</p>

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin') }}">
            Administrator Area
        </a>
    @endif

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
