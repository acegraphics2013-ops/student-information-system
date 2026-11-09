
<div class="mt-4">
    <p>
        Welcome, {{ auth()->user()->name }}!
    </p>

    <p>
        Role: {{ ucfirst(auth()->user()->role) }}
    </p>

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.index') }}"
           class="text-blue-600 underline">
            Open Administrator Panel
        </a>
    @endif
</div>
