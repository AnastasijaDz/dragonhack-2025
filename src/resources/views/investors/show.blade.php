{{-- resources/views/investors/show.blade.php --}}

<div class="container">
    <h1>Investor Details</h1>
    <div class="card">
        <div class="card-header">
            <h3>{{ $investor->name }}'s Profile</h3>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> {{ $investor->email }}</p>
            <p><strong>Phone:</strong> {{ $investor->phone }}</p>

            <h4>Associated User Details:</h4>
            @if($investor->user)
                <p><strong>User Name:</strong> {{ $investor->user->name }}</p>
                <p><strong>User Email:</strong> {{ $investor->user->email }}</p>
            @else
                <p>No associated user found.</p>
            @endif
        </div>
    </div>
</div>
