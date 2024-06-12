<!-- begin: Edit Profile -->
<div class="card-header d-flex justify-content-between">
    <div class="iq-header-title">
        <h4 class="card-title">Edit Profile</h4>
    </div>
</div>
<div class="card-body">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('put')
        <!-- begin: Input Image -->
        <div class="form-group row align-items-center">
            <div class="col-md-12">
                <div class="profile-img-edit">
                    <div class="crm-profile-img-edit">
                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ $user->photo ? asset('storage/profile/'.$user->photo) : asset('assets/images/user/1.png') }}" alt="profile-pic">
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group mb-4">
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
        <!-- end: Input Image -->
        <!-- begin: Input Data -->
        <div class=" row align-items-center">
            <div class="form-group col-md-12">
                <label for="name">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="username">Username <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <!-- end: Input Data -->
        <!-- begin: Input Customer Data -->
        @if(auth()->user()->hasRole('Customer'))
        <div class="form-group row align-items-center">
            <div class="form-group col-md-6">
                <label for="custnum">Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('custnum') is-invalid @enderror" id="custnum" name="custnum" value="{{ old('custnum', optional($customer)->custnum) }}" required>
                <script>
                    // Check if the address is '000', then clear the input box
                    if ("{{ optional($customer)->custnum }}" === '08') {
                        document.getElementById('custnum').value = '';
                    }
                </script>
                @error('custnum')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="custgender">Gender</label>
                <select class="form-control @error('custgender') is-invalid @enderror" id="custgender" name="custgender">
                    <option value="">Select Gender</option>
                    <option value="M" {{ old('custgender', optional($customer)->custgender) == 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ old('custgender', optional($customer)->custgender) == 'F' ? 'selected' : '' }}>Female</option>
                </select>
                @error('custgender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-12">
                <label for="custaddress">Address <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('custaddress') is-invalid @enderror" id="custaddress" name="custaddress" value="{{ old('custaddress', optional($customer)->custaddress) }}" required>
                <script>
                    // Check if the address is '000', then clear the input box
                    if ("{{ optional($customer)->custaddress }}" === '000') {
                        document.getElementById('custaddress').value = '';
                    }
                </script>
                @error('custaddress')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        @endif
        <!-- end: Input Customer Data -->

        <div class="mt-2">
            <button type="submit" class="btn btn-primary mr-2">Update</button>
            <a class="btn bg-danger" href="{{ route('profile') }}">Cancel</a>
        </div>
    </form>
</div>
<!-- end: Edit Profile -->
