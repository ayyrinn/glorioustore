@extends('customerdashboard.body.main')

@section('container')
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/detailproductcustomer.css') }}" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-3">Detail Transaksi</h4>
                    @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between align-items-center mb-3" data-id="{{ $item->id }}">
                            <div>
                                @if($item->product)
                                    <img src="{{ $item->product->product_image ? asset('storage/products/' . $item->product->product_image) : asset('assets/images/product/default.webp') }}" class="img-fluid rounded" alt="{{ $item->product->productname }}" style="width: 100px;">
                                @else
                                    <img src="{{ asset('assets/images/product/default.webp') }}" class="img-fluid rounded" alt="Default Image"  style="width: 100px;">
                                @endif
                            </div>
                            <div class="item-info justify-content-start align-items-center" style="width: 100%;">
                                <h5>{{ $item->name }}</h5>
                                <p class="mb-0 ml-auto">Price: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="mx-2 quantity">{{ $item->qty }}</span>
                            </div>
                        </div>
                    @endforeach
                    <div class="total-section">
                        <p class="label">Subtotal</p>
                        <p class="value">Rp{{ number_format($total, 0, ',', '.') }}</p>
                    </div>
                    <div class="total-section">
                        <p class="label">Pengiriman</p>
                        <p class="value">Rp10.000</p>
                    </div>
                    <div class="total-section">
                        <h4 class="label">Total</h4>
                        <h4 class="value">Rp{{ number_format($total + 10000, 0, ',', '.') }}</h4>
                    </div>
                    <div class="total-section">
                        <p class="label"> </p>
                        <p class="value">(+{{floor($total / 150000)}}poin)</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-3">Pelanggan</h4>
                        <form method="POST" action="{{ route('transaksi.storeOnlineOrder') }}">
                            @csrf
                            <input type="hidden" name="customerid" value="{{ $customer->customerid }}">
                            <div class="form-group">
                                <label for="payment">Choose a payment method:</label>
                                <select class="form-control @error('payment') is-invalid @enderror" name="payment" id="payment">
                                    <option selected="" disabled="">-- Select Payment --</option>
                                    <option value="CASH">CASH ON DELIVERY</option>
                                    <option value="DEBIT">DEBIT</option>
                                    <option value="OTHER">OTHER</option>
                                </select>
                                @error('payment')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            <div class="card-body">
                                <h5>{{ $customer->custname }}</h5>
                                <div class="profilecustomer">
                                    <span>{{ $customer->custemail }}</span> <br>
                                    <span>{{ $customer->custnum }}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6>Alamat pengiriman</h6>
                                <p>{{ $customer->custaddress }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-block">Pilih Alamat Lain</a>


                            <div class="card-body">
                                    <h4 class="mb-3">Catatan</h4>
                                    <div class="form-group">
                                        <textarea class="form-control" rows="3" placeholder="Tinggalkan catatan"></textarea>
                                    </div>
                            </div>

                            <button type="submit" class="btn btn-pesan btn-block">Pesan</button>
                        </form>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        function updateTotal() {
            let total = 0;
            $('.quantity').each(function() {
                const quantity = parseInt($(this).text());
                const price = parseFloat($(this).closest('.d-flex').find('p.mb-0').text().replace('Price: Rp ', '').replace(/\./g, '').replace(',', '.'));
                total += quantity * price;
            });
            $('#total').text(total.toLocaleString('id-ID'));
        }
    });
</script>
</body>
</html>
@endsection