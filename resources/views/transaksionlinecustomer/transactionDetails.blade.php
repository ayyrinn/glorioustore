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

        <!-- Order Summary -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Order #{{ $transaction->transactionid }}</h4>
                        <p style="margin: 0%">{{ $transaction->created_at->format('M d, Y, h:i:s A') }} </p>
                        <p class="metodepembayaran"> Metode Pembayaran: {{ $transaction->payment}}</p>
                    </div>
                    <div>
                        <span class="badge badge-primary">Online</span>
                        <span class="badge badge-secondary">{{ $transaction->status }}</span>
                    </div>
                </div>
                <div class="card-header d-flex justify-content-between">
                    <span>Kurir:</span>
                    <br>
                    <span>{{ $onlinetransaction->employee ? $onlinetransaction->employee->name : 'N/A' }}</span>
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Transaksi</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive rounded mb-3">
                        <table class="table mb-0">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th>No.</th>
                                    <th>Photo</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body">
                                @foreach ($transactionDetails as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img class="avatar-60 rounded"src="{{ $item->product->product_image ? asset('storage/products/' . $item->product->product_image) : asset('assets/images/product/default.webp') }}" class="img-fluid rounded" alt="{{ $item->product->productname }}">

                                    </td>
                                    <td>{{ $item->product->productname }}</td>
                                    <td>{{ $item->product->productid }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Subtotal</strong></td>
                                    <td>Rp{{ number_format($transaction->total, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Pengiriman</strong></td>
                                    <td>Rp10.000</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Total</strong></td>
                                    <td>Rp{{ number_format($transaction->total+10000, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Details and Status Timeline -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pelanggan</h5>
                    <p><strong>{{ $transaction->customer->custname }}</strong></p>
                    <p>{{ $transaction->customer->custnum }}</p>
                    <p>{{ $transaction->customer->custaddress }}</p>
                    <p>{{ $transaction->customer->custnote }}</p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Status</h5>
                    <div>
                        <h5> {{$onlinetransaction->status}}</h5>
                        <p>Pesanan Dibuat <span class="float-right">{{ $transaction->created_at->format('d-m-Y H:i') }}</span></p>
                        

                        @if ($onlinetransaction->status == 'DIKEMAS')
                            <p>Pesanan dikemas <span class="float-right">{{ $transaction->updated_at->format('d-m-Y H:i') }}</span></p>            
                        @endif

                        @if ($onlinetransaction->status == 'DITERIMA')
                            <p>Pesanan diterima <span class="float-right">{{ $transaction->updated_at->format('d-m-Y H:i') }}</span></p>            
                        @endif

                        
                        @if ($onlinetransaction->status == 'DIKIRIM')
                            <p>Pesanan dikirim <span class="float-right">{{ $transaction->updated_at->format('d-m-Y H:i') }}</span></p>            
                        @endif

                        @if ($onlinetransaction->status == 'DIBATALKAN')
                            <p>Pesanan dibatalkan <span class="float-right">{{ $transaction->updated_at->format('d-m-Y H:i') }}</span></p>            
                        @endif
                    </div>
                </div>
            </div>
            @if (($onlinetransaction->status == 'BELUM BAYAR') && ($transaction->payment == 'DEBIT' || $transaction->payment == 'OTHER'))
                <form action="{{ route('transaksi.payment', ['transactionid' => $transaction->transactionid]) }}" method="GET">
                    <button type="submit" class="btn btn-pesan btn-block">Bayar Sekarang</button>
                </form>                
            @endif
       

        </div>


    </div>
    <!-- Page end  -->
</div>
</body>
@endsection
