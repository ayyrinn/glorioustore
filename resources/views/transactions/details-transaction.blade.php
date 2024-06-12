@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Information Transaction Details</h4>
                    </div>
                </div>

                <div class="card-body">
                    <!-- begin: Show Data -->
                    <div class="row align-items-center">
                        <div class="form-group col-md-6">
                            <label>Customer Name</label>
                            <input type="text" class="form-control bg-white" value="{{ $transaction->customer->custname }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Customer Phone</label>
                            <input type="text" class="form-control bg-white" value="{{ $transaction->customer->custnum }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Cashier Name</label>
                            <input type="text" class="form-control bg-white" value="{{ $transaction->employee->name }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Transaction Date</label>
                            <input type="text" class="form-control bg-white" value="{{ $transaction->date }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Transaction Invoice</label>
                            <input class="form-control bg-white" id="buying_date" value="{{ $transaction->transactionid }}" readonly/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Payment Method</label>
                            <input class="form-control bg-white" id="expire_date" value="{{ $transaction->payment }}" readonly />
                        </div>
                    </div>
                    <!-- end: Show Data -->
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
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach ($transactionDetails as $item)
                        <tr>
                            <td>{{ $loop->iteration  }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $item->product->product_image ? asset('storage/products/'.$item->product_image) : asset('assets/images/product/default.webp') }}">
                            </td>
                            <td>{{ $item->product->productname }}</td>
                            <td>{{ $item->product->productid }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->total }}</td>
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
