@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">

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
            <div>
                <h4 class="mb-3">New Order</h4>
            </div>
        </div>

        <div class="col-lg-7 col-md-12">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <form action="#" method="get">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="form-group row">
                                <label for="row" class="align-self-center mx-2">Row:</label>
                                <div>
                                    <select class="form-control" name="row">
                                        <option value="10" @if(request('row') == '10')selected="selected"@endif>10</option>
                                        <option value="25" @if(request('row') == '25')selected="selected"@endif>25</option>
                                        <option value="50" @if(request('row') == '50')selected="selected"@endif>50</option>
                                        <option value="100" @if(request('row') == '100')selected="selected"@endif>100</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-3 align-self-center" for="search">Search:</label>
                                <div class="input-group col-sm-8">
                                    <input type="text" id="search" class="form-control" name="search" placeholder="Search product" value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text bg-primary"><i class="fa-solid fa-magnifying-glass font-size-20"></i></button>
                                        <a href="{{ route('products.index') }}" class="input-group-text bg-danger"><i class="fa-solid fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


                    <div class="table-responsive rounded mb-3 border-none">
                        <table class="table mb-0">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th>No.</th>
                                    <th>Photo</th>
                                    <th>@sortablelink('productname', 'name')</th>
                                    <th>@sortablelink('price', 'price')</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body">
                                @forelse ($products as $product)
                                <tr>
                                    <td>{{ (($products->currentPage() * 10) - 10) + $loop->iteration  }}</td>
                                    <td>
                                        <img class="avatar-60 rounded" src="{{ $product->product_image ? asset('storage/products/'.$product->product_image) : asset('assets/images/product/default.webp') }}">
                                    </td>
                                    <td>{{ $product->productname }}</td>
                                    <td>Rp {{ number_format( $product->price , 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('order.addCart') }}" method="POST"  style="margin-bottom: 5px">
                                            @csrf
                                            <input type="hidden" name="productid" value="{{ $product->productid }}">
                                            <input type="hidden" name="productname" value="{{ $product->productname }}">
                                            <input type="hidden" name="price" value="{{ $product->price }}">

                                            <button type="submit" class="btn btn-primary border-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add"><i class="far fa-plus mr-0"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                @empty
                                <div class="alert text-white bg-danger" role="alert">
                                    <div class="iq-alert-text">Data not Found.</div>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-12 mb-3">
            <table class="table">
                <thead>
                    <tr class="ligth">
                        <th scope="col">Name</th>
                        <th scope="col">QTY</th>
                        <th scope="col">Price</th>
                        <th scope="col">SubTotal</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productItem as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td style="min-width: 140px;">
                            <form action="{{ route('order.updateCart', $item->rowId) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="number" class="form-control" name="qty" required value="{{ old('qty', $item->qty) }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success border-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sumbit"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td style="min-width: 140px;">
                            <form action="{{ route('order.updateCart', $item->rowId) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="number" class="form-control" name="price" required value="{{ old('price', $item->price, 0, ',', '.') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success border-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sumbit"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td>Rp {{ number_format( $item->subtotal , 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('order.deleteCart', $item->rowId) }}" class="btn btn-danger border-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa-solid fa-trash mr-0"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row">
                <div class="col-lg-6 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <p class="h4 text-primary">Quantity: {{ Cart::instance('supplier')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <p class="h4 text-primary">Total: Rp {{ number_format(Cart::instance('supplier')->total(), 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('order.createInvoice') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <select class="form-control" id="supplierid" name="supplierid">
                                <option selected="" disabled="">-- Select Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->supplierid }}">{{ $supplier->supname }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('supplierid')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-center">
                            <a href="{{ route('customers.create') }}" class="btn btn-primary add-list mx-1">Add Supplier</a>
                            <button type="submit" class="btn btn-success add-list mx-1">Create Invoice</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
