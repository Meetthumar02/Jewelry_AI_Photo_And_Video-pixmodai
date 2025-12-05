<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register | JW AI</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="auth-card mx-auto">
      <div class="text-center mb-4">
        <h3 class="auth-title">Create Account ✨</h3>
        <p class="auth-subtitle">Join JW AI and start your journey</p>
      </div>

      {{-- Success Message --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      {{-- Error Message --}}
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      {{-- Validation Errors --}}
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('register.post') }}" id="registerForm">
        @csrf

        <div class="mb-3">
          <label class="form-label fw-semibold">Full Name *</label>
          <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter your full name" required>
        </div>

        <!-- Email & Phone in same row -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter your email" required>
            @error('email')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Phone Number *</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="Enter phone number" maxlength="10" pattern="[0-9]{10}" required>
            @error('phone')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Password *</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 8 characters" minlength="8" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Confirm Password *</label>
          <input type="password" name="password_confirmation" id="confirm_password" class="form-control" placeholder="Confirm your password" required>
        </div>

        <button type="submit" class="btn btn-custom w-100 py-2 mt-2">
          Create Account
        </button>
      </form>

      <div class="text-center mt-4">
        <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-link">Sign in here</a></p>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Allow only numbers in phone field
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function () {
      this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Password match & validation
    const form = document.getElementById('registerForm');
    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');

    form.addEventListener('submit', (e) => {
      if (password.value.length < 8) {
        alert("Password must be at least 8 characters long!");
        e.preventDefault();
        return;
      }
      if (password.value !== confirm_password.value) {
        alert("Passwords do not match!");
        e.preventDefault();
      }
    });

    confirm_password.addEventListener('keyup', () => {
      if (confirm_password.value && password.value !== confirm_password.value) {
        confirm_password.style.border = "2px solid #dc2626";
      } else {
        confirm_password.style.border = "2px solid #16a34a";
      }
    });
  </script>
</body>
</html>
