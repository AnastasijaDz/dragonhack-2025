@extends('layouts.app')  <!-- Extends your layout file -->

@section('content')  <!-- Define the content section to be rendered in the layout -->
    <div class="container">
        <h1>Projects</h1>
        
        <!-- Check if there are any projects -->
        @if($projects->isEmpty())
            <p>No projects available.</p>
        @else
            <!-- Loop through the projects and display each one -->
            <div class="row">
                @foreach($projects as $project)
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $project->name }}</h5>
                                <p class="card-text">{{ $project->description }}</p>
                                <p class="card-text">{{ $project->price }}</p>
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary">View Project</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
