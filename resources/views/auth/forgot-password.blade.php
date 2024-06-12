@extends('auth.body.main')

@section('container')
<div class="row align-items-center" style="height: 100vh;">
    <div class="col-lg-7 col-md-6 col-12 p-0" style="height: 100vh;">
        <img src="{{ asset('assets/images/login/03.jpg') }}" class="img-fluid" style="height: 100vh; width: 100%; object-fit: cover;" alt="Login Image">
    </div>
    <div class="col-lg-5 col-md-6 col-12 d-flex align-items-center justify-content-center p-0" style="height: 100vh; background-color: white;">
        <div class="auth-card" style="width: 100%; max-width: 500px; padding: 40px;">
            <div class="card-body p-0 d-flex flex-column align-items-center">
                <div class="auth-content" style="width: 100%;">
                    <div class="p-3 text-left">
                        <img src="{{ asset('../assets/images/logo.png') }}" alt="Logo Toko" style="width: 70px; margin-bottom: 20px; display: block; margin-left: auto; margin-right: auto;">
                        <h2 class="mb-2 text-center">Lupa Kata Sandi</h2>
                        <p class="text-center">Masukkan email Anda untuk menerima tautan reset kata sandi.</p>
                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="email" style="text-align: left;">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" style="font-size: 1.15rem; padding: 12px;">Kirim Tautan Reset</button>
                            <div class="text-center mt-3">
                                <p>atau</p>
                                <p>
                                    Kembali ke <a href="{{ route('login') }}" class="text-primary">Login</a>
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
