@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Barcode</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class=" row align-items-center">
                        <div class="form-group col-md-6">
                            <label>Product ID</label>
                            <input type="text" class="form-control bg-white" value="{{  $product->productid }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Product Barcode</label>
                            {!! $barcode !!}
                        </div>
                    </div>
                    <!-- end: Show Data -->
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Information Product</h4>
                    </div>
                </div>

                <div class="card-body">
                    <!-- begin: Show Data -->
                    <div class="form-group row align-items-center">
                        <div class="col-md-12">
                            <div class="profile-img-edit">
                                <div class="crm-profile-img-edit">
                                    <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ $product->product_image ? asset('storage/products/'.$product->product_image) : asset('assets/images/product/default.webp') }}" alt="profile-pic">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" row align-items-center">
                        <div class="form-group col-md-12">
                            <label>Product Name</label>
                            <input type="text" class="form-control bg-white" value="{{  $product->productname }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Category</label>
                            <input type="text" class="form-control bg-white" value="{{  $product->category->name }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Supplier</label>
                            <input type="text" class="form-control bg-white" value="{{  $product->supplier->name }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Stock</label>
                            <input type="text" class="form-control bg-white" value="{{  $product->stock }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Price</label>
                            <input type="text" class="form-control bg-white" value="{{  $product->price }}" readonly>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Description</label>
                            <input type="text" class="form-control bg-white" value="{{  $product->productname }}" readonly>
                        </div>
                    </div>
                    <!-- end: Show Data -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>

@include('components.preview-img-form')
@endsection
