@extends('auth.body.main')

@section('container')
<div class="row align-items-center" style="height: 100vh;">
  <div class="col-lg-7 col-md-6 col-12 p-0" style="height: 100vh;">
    <img src="{{ asset('assets/images/login/01.png') }}" class="img-fluid" style="height: 100vh; width: 100%; object-fit: cover;" alt="Login Image">
  </div>
  <div class="col-lg-5 col-md-6 col-12 d-flex align-items-center justify-content-center p-0" style="height: 100vh; background-color: white;">
    <div class="auth-card d-flex justify-content-center" style="width: 100%; max-width: 500px; padding: 40px;">
      <div class="card-body p-0 d-flex flex-column align-items-center">
        <div class="auth-content" style="width: 100%;">
          <div class="p-3 text-center">
            <img src="{{ asset('../assets/images/logo.png') }}" alt="Logo Toko" style="width: 50px; margin-bottom: 20px;">
            <h2 class="mb-2">Daftar Akun</h2>
            <p>Buat Akun Anda</p>
            <form method="POST" action="{{ route('register') }}">
              @csrf
              <div class="row">
                <div class="col-lg-12">
                  <div class="floating-label form-group">
                    <input class="floating-input form-control @error('name') is-invalid @enderror" type="text" placeholder=" " name="name" autocomplete="off" value="{{ old('name') }}" required>
                    <label>Nama Lengkap</label>
                  </div>
                  @error('name')
                  <div class="mb-4" style="margin-top: -20px">
                    <div class="text-danger small">{{ $message }}</div>
                  </div>
                  @enderror
                </div>

                <div class="col-lg-12">
                  <div class="floating-label form-group">
                    <input class="floating-input form-control @error('username') is-invalid @enderror" type="text" placeholder=" " name="username" autocomplete="off" value="{{ old('username') }}" required>
                    <label class="mb-1">Username</label>
                  </div>
                  @error('username')
                  <div class="mb-4" style="margin-top: -20px">
                    <div class="text-danger small">{{ $message }}</div>
                  </div>
                  @enderror
                </div>

                <div class="col-lg-12">
                  <div class="floating-label form-group">
                    <input class="floating-input form-control @error('email') is-invalid @enderror" type="email" placeholder=" " name="email" autocomplete="off" value="{{ old('email') }}" required>
                    <label>Email</label>
                  </div>
                  @error('email')
                  <div class="mb-4" style="margin-top: -20px">
                    <div class="text-danger small">{{ $message }}</div>
                  </div>
                  @enderror
                </div>

                <div class="col-lg-6">
                  <div class="floating-label form-group">
                    <input class="floating-input form-control @error('password') is-invalid @enderror" type="password" placeholder=" " name="password" autocomplete="off" required>
                    <label>Password</label>
                  </div>
                  @error('password')
                  <div class="mb-4" style="margin-top: -20px">
                    <div class="text-danger small">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
                <div class="col-lg-6">
                  <div class="floating-label form-group">
                    <input class="floating-input form-control" type="password" placeholder=" " name="password_confirmation" autocomplete="off" required>
                    <label>Konfirmasi Password</label>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-block" style="font-size: 1.15rem; padding: 12px;">Daftar Akun</button>
              <p class="mt-3">
                Sudah Mempunyai Akun? <a href="{{ route('login') }}" class="text-primary">Log In</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
