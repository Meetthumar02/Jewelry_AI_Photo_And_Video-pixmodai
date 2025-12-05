@extends('user.app')
@section('title', 'Settings')

@section('content')
    <div class="p-4">
        <h4 class="fw-semibold mb-3">Account Settings</h4>
        <p class="text-muted mb-4">Update your personal details and preferences.</p>

        <div class="card p-4 shadow-sm" style="max-width: 600px;">
            <form>
                <div class="mb-3">
                    <label class="form-label fw-medium">Name</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name ?? '' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Email</label>
                    <input type="email" class="form-control" value="{{ Auth::user()->email ?? '' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Change Password</label>
                    <input type="password" class="form-control" placeholder="••••••••">
                </div>
                <button class="btn btn-gradient px-4 py-2"><i class="fas fa-save me-2"></i> Save Changes</button>
            </form>
        </div>
    </div>
@endsection
