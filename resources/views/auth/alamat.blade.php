@extends('auth.body.main')

@section('container')
<div class="row align-items-center" style="height: 100vh;">
  <div class="col-lg-7 col-md-6 col-12 p-0" style="height: 100vh;">
    <img src="{{ asset('assets/images/login/03.jpg') }}" class="img-fluid" style="height: 100vh; width: 100%; object-fit: cover;" alt="Login Image">
  </div>
  <div class="col-lg-5 col-md-6 col-12 d-flex align-items-center justify-content-center p-0" style="height: 100vh; background-color: white;">
    <div class="auth-card d-flex justify-content-center" style="width: 100%; max-width: 500px; padding: 40px;">
      <div class="card-body p-0 d-flex flex-column align-items-center">
        <div class="auth-content" style="width: 100%;">
          <div class="p-3 text-center">
            <img src="{{ asset('../assets/images/logo.png') }}" alt="Logo Toko" style="width: 50px; margin-bottom: 20px;">
            <h2 class="mb-2">Lengkapi Profil</h2>
            <p>Masukkan Detail Profil Anda</p>
            <form method="POST" action="{{ route('alamat.store') }}">
              @csrf
              <div class="row">
                <div class="col-lg-12">
                  <div class="floating-label form-group">
                    <input class="floating-input form-control @error('custaddress') is-invalid @enderror" type="text" placeholder=" " name="custaddress" autocomplete="off" value="{{ old('custaddress', $customer->custaddress) }}" required>
                    <label>Alamat Utama</label>
                  </div>
                  @error('custaddress')
                  <div class="mb-4" style="margin-top: -20px">
                    <div class="text-danger small">{{ $message }}</div>
                  </div>
                  @enderror
                </div>

                <div class="col-lg-12">
                  <div class="floating-label form-group">
                    <input class="floating-input form-control @error('custnum') is-invalid @enderror" type="text" placeholder=" " name="custnum" autocomplete="off" value="{{ old('custnum', $customer->custnum) }}" required>
                    <label class="mb-1">Nomor Telepon</label>
                  </div>
                  @error('custnum')
                  <div class="mb-4" style="margin-top: -20px">
                    <div class="text-danger small">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-block" style="font-size: 1.15rem; padding: 12px;">Simpan Profil</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
