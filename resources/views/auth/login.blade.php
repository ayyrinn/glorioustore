@extends('auth.body.main')

@section('container')
<div class="row align-items-center" style="height: 100vh;">
  <div class="col-lg-7 col-md-6 col-12 p-0" style="height: 100vh;">
    <img src="{{ asset('assets/images/login/02.jpg') }}" class="img-fluid" style="height: 100vh; width: 100%; object-fit: cover;" alt="Login Image">
  </div>
  <div class="col-lg-5 col-md-6 col-12 d-flex align-items-center justify-content-center p-0" style="height: 100vh; background-color: white;">
    <div class="auth-card" style="width: 100%; max-width: 500px; padding: 40px;">
      <div class="card-body p-0 d-flex flex-column align-items-center">
        <div class="auth-content" style="width: 100%;">
          <div class="p-3 text-left"> <!-- Changed text-center to text-left -->
          <img src="{{ asset('../assets/images/logo.png') }}" alt="Logo Toko" style="width: 70px; margin-bottom: 20px; display: block; margin-left: auto; margin-right: auto;">
          <h2 class="mb-2 text-center">Masuk ke Akun Anda</h2>
          <p class="text-center">Masuk untuk selalu terhubung.</p>
            <form action="{{ route('login') }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="input_type" style="text-align: left;">Username</label> <!-- Added style="text-align: left;" -->
                <input type="text" class="form-control @error('email') is-invalid @enderror @error('username') is-invalid @enderror" 
                       id="input_type" name="input_type" placeholder="Masukkan username atau email" value="{{ old('input_type') }}" required autofocus>
                @error('username')
                <div class="text-danger small">Username atau kata sandi yang anda masukkan salah!</div>
                @enderror
                @error('email')
                <div class="text-danger small">Username atau kata sandi yang anda masukkan salah!</div>
                @enderror
              </div>
              <div class="form-group">
                <label for="password" style="text-align: left;">Kata Sandi</label> <!-- Added style="text-align: left;" -->
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan kata sandi" required>
              </div>
              <div class="form-group form-check d-flex justify-content-between">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Ingat Saya</label>
                <a href="{{ route('password.request') }}" class="text-primary">Lupa Kata Sandi?</a>
              </div>
              <button type="submit" class="btn btn-primary btn-block" style="font-size: 1.15rem; padding: 12px;">Login</button>
              <div class="text-center mt-3">
                <p>atau</p>
                <p>
                  Apakah Anda Tidak Mempunyai Akun? <a href="{{ route('register') }}" class="text-primary">Daftar Disini</a>
                </p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
