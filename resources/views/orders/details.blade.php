@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Information Order Details</h4>
                    </div>
                </div>

                <div class="card-body">
                    <!-- begin: Show Data -->
                    <div class="row align-items-center">
                        <div class="form-group col-md-12">
                            <label>Supplier Name</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->supplier->supname }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Supplier Number</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->supplier->supnumber }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Supplier Address</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->supplier->supaddress }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Order Date</label>
                            <input type="text" class="form-control bg-white" value="{{ $order->date }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Order Invoice</label>
                            <input class="form-control bg-white" id="buying_date" value="{{ $order->orderid }}" readonly/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Order Status</label>
                            <input class="form-control bg-white" id="expire_date" value="{{ $order->status }}" readonly />
                        </div>
                    </div>
                    <!-- end: Show Data -->

                    @if ($order->status == 'BELUM DITERIMA')
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex align-items-center list-action">
                                    <form action="{{ route('order.updateStatus') }}" method="POST" style="margin-bottom: 5px">
                                        @method('put')
                                        @csrf
                                        <input type="hidden" name="orderid" value="{{ $order->orderid }}">
                                        <button type="submit" class="btn btn-success mr-2 border-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Complete">Order Received</button>

                                        <a class="btn btn-danger mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel" href="{{ route('order.index') }}">Cancel</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        <!-- end: Show Data -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No.</th>
                            <th>Photo</th>
                            <th>Product Name</th>
                            <th>Product Code</th>
                            <th>Quantity</th>
                            <th>Buying Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach ($orderDetails as $item)
                        <tr>
                            <td>{{ $loop->iteration  }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $item->product->product_image ? asset('storage/products/'.$item->product->product_image) : asset('assets/images/product/default.webp') }}">
                            </td>
                            <td>{{ $item->product->productname }}</td>
                            <td>{{ $item->product->productid }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp{{ number_format( $item->buying_price , 0, ',', '.') }}</td>
                            <td>Rp{{ number_format( $item->subtotal , 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>

@include('components.preview-img-form')
@endsection
