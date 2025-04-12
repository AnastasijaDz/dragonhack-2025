@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('projects') }}" class="btn btn-secondary mb-3">← Back to Projects</a>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{ $project->name }}</h2>
            <p class="card-text">{{ $project->description }}</p>
            
            <!-- Optional: Add more project details here -->
            {{-- <p><strong>Created at:</strong> {{ $project->created_at->format('d M Y') }}</p> --}}
        </div>
    </div>
</div>
@endsection
