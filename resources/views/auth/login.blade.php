<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | JW AI</title>

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
    <h3 class="auth-title">Welcome Back 👋</h3>
    <p class="auth-subtitle">Sign in to access your JW AI</p>
  </div>

      @if(session('error'))
        <div class="error-text mb-3">{{ session('error') }}</div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn btn-custom w-100 py-2 mt-2">
          Continue with Email
        </button>
      </form>

      <div class="text-center mt-4">
        <p class="mb-0">New to ? <a href="{{ route('register') }}" class="text-link">Sign up here</a></p>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Optional small JS for form animation -->
  <script>
    document.querySelector('.login-card').style.opacity = 0;
    window.addEventListener('load', () => {
      document.querySelector('.login-card').style.transition = 'opacity 0.8s ease';
      document.querySelector('.login-card').style.opacity = 1;
    });
  </script>
</body>
</html>
