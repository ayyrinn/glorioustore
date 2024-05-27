@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Add Customer</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <!-- begin: Input Image -->
                        <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <div class="profile-img-edit">
                                    <div class="crm-profile-img-edit">
                                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ asset('assets/images/user/1.png') }}" alt="profile-pic">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-group mb-4 col-lg-6">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" id="image" name="photo" accept="image/*" onchange="previewImage();">
                                    <label class="custom-file-label" for="photo">Choose file</label>
                                </div>
                                @error('photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Input Image -->

                        <!-- begin: Input Data -->
                        <div class="row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="custname">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('custname') is-invalid @enderror" id="custname" name="custname" value="{{ old('custname') }}" required>
                                @error('custname')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="custname">Customer Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('custemail') is-invalid @enderror" id="custemail" name="custemail" value="{{ old('custemail') }}" required>
                                @error('custemail')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="custnum">Customer Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('custnum') is-invalid @enderror" id="custnum" name="custnum" value="{{ old('custnum') }}" required>
                                @error('custnum')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="custgender">Customer Gender</label>
                                <select class="form-control @error('custgender') is-invalid @enderror" id="custgender" name="custgender">
                                    <option value="">Select Gender</option>
                                    <option value="M" {{ old('custgender') == 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ old('custgender') == 'F' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('custgender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="points">Points <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('points') is-invalid @enderror" id="points" name="points" value="{{ old('points') }}" required>
                                @error('points')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="custaddress">Customer Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('custaddress') is-invalid @enderror" id="custaddress" name="custaddress" required>{{ old('custaddress') }}</textarea>
                                @error('custaddress')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Input Data -->

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Save</button>
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

<script>
function previewImage() {
    const image = document.querySelector('#image');
    const imgPreview = document.querySelector('#image-preview');

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);

    oFReader.onload = function(oFREvent) {
        imgPreview.src = oFREvent.target.result;
    };
}
</script>
