@extends('customerdashboard.body.main')

@section('container')
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/catalogcustomer.css') }}">
</head>
<body>

<div class="container-fluid">
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <div class="row">
        <div class="col-lg-12">
            @if (session()->has('success'))
                <div class="alert text-white bg-success" role="alert">
                    <div class="iq-alert-text">{{ session('success') }}</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif
        </div>
        
        <!-- Start of Cart Section -->
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Keranjang</h4>
                </div>
            </div>
            <div class="table-responsive rounded mb-3 ">
                <table class="table mb-0">
                    <tbody>
                        <!-- Loop through the cart items -->
                        @foreach ($cartItems as $item)
                        <tr data-rowid="{{ $item->rowId }}">
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input item-check" type="checkbox" value="{{ $item->rowId }}" data-price="{{ $item->price }}" data-qty="{{ $item->qty }}" id="defaultCheck{{ $item->rowId }}">
                                </div>
                            </td>
                            <td>
                                @if($item->product)
                                    <img src="{{ $item->product->product_image ? asset('storage/products/' . $item->product->product_image) : asset('assets/images/product/default.webp') }}" class="img-fluid rounded" alt="{{ $item->product->productname }}" style="width: 100px;">
                                @else
                                    <img src="{{ asset('assets/images/product/default.webp') }}" class="img-fluid rounded" alt="Default Image"  style="width: 100px;">
                                @endif
                            </td>
                            <td>
                                <h5>{{ $item->name }}</h5>
                                <p>Price</p>
                                <p>Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-primary quantity-btn decrease">-</button>
                                    <input type="number" value="{{ $item->qty }}" min="1" max="{{ $item->product->stock }}" class="form-control text-center mx-2 quantity-input" style="width: 60px;" readonly>
                                    <button type="button" class="btn btn-primary quantity-btn increase">+</button>
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('keranjang.deleteCart', $item->rowId) }}" method="post" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger delete-btn">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="container-fluid rounded mt-4" style="background-color: white; padding: 15px;">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="mt-2">
                                <input type="checkbox" id="select-all">
                                <label for="select-all">Pilih Semua ({{ count($cartItems) }})</label>
                            </div>
                            <div class="ml-auto mt-2 mr-3">
                                <h4>Total (<span id="total-items">{{ count($cartItems) }}</span> produk): Rp <span id="total-price">{{ number_format(Cart::total(), 0, ',', '.') }}</span></h4>
                            </div>
                            <div>
                                <a href="{{ route('transaksi.createOnlineOrder', ['customerid' => $customerId]) }}" class="btn btn-pesan btn-block">+ Pesan Online</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            

        </div>
        <!-- End of Cart Section -->
        
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.quantity-btn').on('click', function() {
            var rowId = $(this).closest('tr').data('rowid');
            var quantityInput = $(this).siblings('.quantity-input');
            var currentValue = parseInt(quantityInput.val());
            var maxValue = parseInt(quantityInput.attr('max'));

            if ($(this).hasClass('increase')) {
                if (currentValue < maxValue) {
                    quantityInput.val(currentValue + 1);
                    updateCart(rowId, currentValue + 1);
                }
            } else if ($(this).hasClass('decrease')) {
                if (currentValue > 1) {
                    quantityInput.val(currentValue - 1);
                    updateCart(rowId, currentValue - 1);
                }
            }
        });

        // Fungsi untuk mengirim perubahan jumlah menggunakan AJAX
        function updateCart(rowId, quantity) {
            $.ajax({
                url: '{{ route("keranjang.updateCart", ":rowId") }}'.replace(':rowId', rowId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    qty: quantity
                },
                success: function(response) {
                    // Muat ulang halaman setelah perubahan berhasil
                    location.reload();
                },
                error: function(response) {
                    alert('Error updating cart. Please try again.');
                }
            });
        }

        $('#select-all').on('change', function() {
            $('.item-check').prop('checked', $(this).prop('checked'));
            updateTotal();
        });

        $('.item-check').on('change', function() {
            updateTotal();
        });

        function updateTotal() {
            var totalPrice = 0;
            var totalItems = 0;

            $('.item-check:checked').each(function() {
                var price = parseFloat($(this).data('price'));
                var qty = parseInt($(this).data('qty'));
                totalPrice += price * qty;
                totalItems += qty;
            });

            $('#total-price').text(totalPrice.toLocaleString('id-ID'));
            $('#total-items').text(totalItems);
        }

        $('.delete-btn').on('click', function() {
            var confirmed = confirm('Apakah Anda yakin ingin menghapus produk ini?');
            if (confirmed) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
</body>

@endsection
