@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Edit Customer</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('customers.update', $customer->customerid) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                        <!-- begin: Input Data -->
                        <div class=" row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="customerid">Customer ID</label>
                                <input type="text" class="form-control" id="customerid" name="customerid" value="{{ $customer->customerid }}" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="custname">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('custname') is-invalid @enderror" id="custname" name="custname" value="{{ old('custname', $customer->custname) }}" required>
                                @error('custname')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="custname">Customer Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('custemail') is-invalid @enderror" id="custemail" name="custemail" value="{{ old('custemail', $customer->custemail) }}" required>
                                @error('custemail')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="custnum">Customer Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('custnum') is-invalid @enderror" id="custnum" name="custnum" value="{{ old('custnum', $customer->custnum) }}" required>
                                @error('custnum')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="custgender">Customer Gender</label>
                                <select class="form-control @error('custgender') is-invalid @enderror" name="custgender">
                                    <option value="">Select Gender..</option>
                                    <option value="M" @if(old('custgender', $customer->custgender) == 'M')selected="selected"@endif>Male</option>
                                    <option value="F" @if(old('custgender', $customer->custgender) == 'F')selected="selected"@endif>Female</option>
                                </select>
                                @error('custgender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="points">Points <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('points') is-invalid @enderror" id="points" name="points" value="{{ old('points', $customer->points) }}" required>
                                @error('points')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="custaddress">Customer Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('custaddress') is-invalid @enderror" name="custaddress" required>{{ old('custaddress', $customer->custaddress) }}</textarea>
                                @error('custaddress')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Input Data -->
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Update</button>
                            <a class="btn bg-danger" href="{{ route('customers.index') }}">Cancel</a>
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
