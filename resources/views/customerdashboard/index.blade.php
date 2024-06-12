@extends('customerdashboard.body.main')
@section('container')

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catalog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/catalogcustomer.css') }}">
</head>
<body>
<div class="catalog">
    <div class="container-23">
        <div class="breadcrumbfilter">
            <div class="seachfilter">
                <div class="categories">
                    @foreach(['Semua', 'Makanan', 'Minuman', 'Home and Living', 'Personal Care', 'Kesehatan', 'Rokok', 'Produk lainnya'] as $category)
                        <div class="tag-categories">
                            <form action="{{ route('customerdashboard.index') }}" method="GET">
                                <input type="hidden" name="category" value="{{ $category }}">
                                <button type="submit" class="placeholder">{{ $category }}</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="catalogisi">
        @foreach($products as $product)
        <div class="card">
            <div onclick="redirectToProductDetail('{{ route('productdetailcustomer.index', ['id' => $product->productid]) }}')">
                <img src="{{ $product->product_image ? asset('storage/products/' . $product->product_image) : asset('assets/images/product/default.webp') }}" class="card-img-top" alt="{{ $product->productname }}">
                <div class="card-body">
                    <h4 class="namaproduk">{{ $product->productname }}</h4>
                    <h3 class="price-2">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                    <span class="quantity">Stock: {{ $product->stock }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('keranjang.addCart') }}">
                @csrf
                <input type="hidden" name="productid" value="{{ $product->productid }}">
                <input type="hidden" name="productname" value="{{ $product->productname }}">
                <input type="hidden" name="price" value="{{ $product->price }}">
                <button type="submit" class="btn btn-keranjang btn-block">+ Keranjang</button>
            </form>
        </div>
        @endforeach
        </div>
    </div>
</div>

    @include('components.preview-img-form')

    <script>
        function redirectToProductDetail(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>

@endsection
