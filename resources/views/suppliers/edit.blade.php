@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Edit Supplier</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('suppliers.update', $supplier->supplierid) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                        <!-- begin: Input Data -->
                        <div class="row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="supplierid">Supplier ID</label>
                                <input type="text" class="form-control" id="supplierid" name="supplierid" value="{{ $supplier->supplierid }}" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="supname">Supplier Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('supname') is-invalid @enderror" id="supname" name="supname" value="{{ old('name', $supplier->supname) }}" required>
                                @error('supname')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="supnumber">Supplier Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('supnumber') is-invalid @enderror" id="supnumber" name="supnumber" value="{{ old('supnumber', $supplier->supnumber) }}" required>
                                @error('supnumber')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="supaddress">Supplier Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('supaddress') is-invalid @enderror" name="supaddress" required>{{ old('supaddress', $supplier->supaddress) }}</textarea>
                                @error('supaddress')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Input Data -->
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Update</button>
                            <a class="btn bg-danger" href="{{ route('suppliers.index') }}">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>

@include('components.preview-img-form')
@endsection
