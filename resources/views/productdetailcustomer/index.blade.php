@extends('customerdashboard.body.main')
@section('container')

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Produk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/detailproductcustomer.css') }}" />
</head>
<body>
<div class="detail-produk container">
    <div> <br><br><br> </div>
    <div class="container-7 p-4">
        <div class="product-details row mb-4">
            <div class="product-image col-md-6 card">
                <img src="{{ $product->product_image ? asset('storage/products/' . $product->product_image) : asset('assets/images/product/default.webp') }}" class="img-fluid rounded" alt="{{ $product->productname }}">
            </div>
            <div class="product-info col-md-6">
                <div class="product-info-harga card">
                    <h1>{{ $product->productname }}</h1>
                    <div class="price-section mb-3">
                        <h3 class=" font-weight-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                    </div>
                    <div class="quantity-section mb-3">
                        <span class="font-weight-bold mr-2">Jumlah</span>
                        <div class="quantity-input d-flex align-items-center">
                            <button type="button" class="btn btn-primary quantity-btn" id="decrease">-</button>
                            <input type="number" value="1" min="1" max="{{ $product->stock }}" id="quantity" class="form-control text-center mx-2 quantity-input" style="width: 60px;">
                            <button type="button" class="btn btn-primary quantity-btn" id="increase">+</button>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('keranjang.addCart') }}">
                        @csrf
                        <input type="hidden" name="productid" value="{{ $product->productid }}">
                        <input type="hidden" name="productname" value="{{ $product->productname }}">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="qty" id="hiddenQuantity" value="1">
                        <button type="submit" class="btn btn-keranjang btn-block">+ Keranjang</button>
                    </form>
                </div>

                <div class="product-description card">
                    <h2>Deskripsi Produk</h2>
                    <p>{{ $product->description }}</p>
                </div>
            </div>
            </div>
        </div>
       
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
   document.getElementById('increase').addEventListener('click', function() {
        var quantityInput = document.getElementById('quantity');
        var currentValue = parseInt(quantityInput.value);
        var maxValue = parseInt(quantityInput.max);
        if (currentValue < maxValue) {
            quantityInput.value = currentValue + 1;
            document.getElementById('hiddenQuantity').value = currentValue + 1;
        }
    });

    document.getElementById('decrease').addEventListener('click', function() {
        var quantityInput = document.getElementById('quantity');
        var currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            document.getElementById('hiddenQuantity').value = currentValue - 1;
        }
    });

    document.getElementById('quantity').addEventListener('change', function() {
        document.getElementById('hiddenQuantity').value = this.value;
    });
</script>
</body>
</html>
@endsection
