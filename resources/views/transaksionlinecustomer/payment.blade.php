@extends('customerdashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6"> <!-- Mengatur lebar form -->
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Pembayaran</h4>
                        <h6>#<span>{{ $transaction->transactionid }}</span></h6>
                    </div>
                    <div class="invoice">
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group col-md-12">
                        <td>Total: Rp{{ number_format($transaction->total, 0, ',', '.') }}</td>
                    </div>
                    <form action="{{ route('transaksi.storePayment') }}" method="POST" enctype="multipart/form-data" class="d-flex justify-content-center flex-column align-items-center"> <!-- Menggunakan kelas flex-column dan align-items-center untuk memusatkan vertikal -->
                        @csrf
                        <input type="hidden" name="transactionid" value="{{ $transaction->transactionid }}">
                        <!-- begin: Input Image -->
                        <div class="input-group mb-4 col-lg-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('proofpayment') is-invalid @enderror" id="image" name="proofpayment" accept="image/*" onchange="previewImage();">
                                <label class="custom-file-label" for="proofpayment">Choose file</label>
                            </div>
                            @error('proofpayment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Upload</button>
                            </div>
                            <a href="{{ route('transaksi.showOrderHistory') }}" class="btn btn-secondary ml-2">Bayar Nanti</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>
@endsection
