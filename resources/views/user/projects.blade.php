@extends('user.app')
@section('title', 'Projects')

@section('content')
    <div class="p-4">
        <h4 class="fw-semibold mb-3">Projects</h4>
        <p class="text-muted mb-4">Organize and manage your design projects here.</p>

        <div class="card p-4 text-center text-muted">
            <i class="fas fa-folder fa-2x mb-2 text-primary"></i>
            <p>No projects found. Start by creating one.</p>
        </div>
    </div>
@endsection
