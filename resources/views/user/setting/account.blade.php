<div class="settings-section {{ $activeTab === 'account' ? 'active' : '' }}" data-section="account">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Account Settings</h4>
            <p class="text-muted mb-0">Update your personal details and profile.</p>
        </div>
        <button class="btn btn-outline-secondary btn-sm px-3" type="button">
            <i class="fas fa-clock me-2"></i>Last synced: Just now
        </button>
    </div>

    <form action="#" method="POST" enctype="multipart/form-data" class="settings-form">
        @csrf
        <div class="profile-photo-block mb-4">
            <div class="profile-photo-avatar" id="profileAvatar" data-initial="{{ $initial }}">
                @if (!empty($user->profile_photo_url ?? ''))
                    <img src="{{ $user->profile_photo_url }}" alt="Profile photo">
                @else
                    {{ $initial }}
                @endif
            </div>
            <div>
                <p class="fw-semibold mb-1">Profile Photo</p>
                <div class="d-flex gap-2 mb-2 flex-wrap">
                    <input type="file" name="avatar" id="profileUploadInput" class="d-none"
                        accept="image/png,image/jpeg,image/gif">
                    <button type="button" class="btn btn-light border px-4"
                        onclick="document.getElementById('profileUploadInput').click();">
                        Upload New
                    </button>
                    <button type="button" class="btn btn-link text-danger p-0" id="removeAvatarBtn">
                        Remove
                    </button>
                </div>
                <small class="text-muted">JPG, PNG, or GIF. Max 5MB</small>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $firstName) }}"
                    placeholder="First Name">
            </div>
            <div class="col-md-6">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $lastName) }}"
                    placeholder="Last Name">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}"
                    placeholder="you@email.com">
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone_number" class="form-control"
                    value="{{ old('phone_number', $user->phone_number ?? '') }}" placeholder="Phone Number">
            </div>
            <div class="col-md-6">
                <label class="form-label">Change Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••">
            </div>
        </div>

        <div class="text-end mt-4">
            <button class="btn btn-gradient px-4" type="submit">
                Save Changes
            </button>
        </div>
    </form>
</div>
