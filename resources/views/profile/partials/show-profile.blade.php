@if (session()->has('success'))
<div class="alert text-white bg-success" role="alert">
    <div class="iq-alert-text">{{ session('success') }}</div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <i class="ri-close-line"></i>
    </button>
</div>
@endif

<div class="card-header d-flex justify-content-between">
    <div class="iq-header-title">
        <h4 class="card-title">Profile</h4>
    </div>
</div>
<div class="card-body">
    <div class="form-group row align-items-center">
        <div class="col-md-12">
            <img class="crm-profile-pic rounded-circle avatar-100" src="{{  auth()->user()->photo ? asset('storage/profile/'. auth()->user()->photo) : asset('assets/images/user/1.png') }}" alt="profile-pic">
        </div>
    </div>
    <div class=" row align-items-center">
        <div class="form-group col-md-12">
            <label for="fname">Full Name</label>
            <input type="text" class="form-control bg-white" id="fname" value="{{  auth()->user()->name }}" readonly>
        </div>
        <div class="form-group col-md-6">
            <label for="uname">Username</label>
            <input type="text" class="form-control bg-white" id="uname" value="{{  auth()->user()->username }}" readonly>
        </div>
        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="text" class="form-control bg-white" id="email" value="{{  auth()->user()->email }}" readonly>
        </div>
    </div>
    <!-- begin: Customer Data -->
    @if(auth()->user()->hasRole('Customer'))
    <div class="form-group row align-items-center">
        <div class="form-group col-md-6">
            <label for="custnum">Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control bg-white" id="custnum" name="custnum" value="{{ $customer->custnum }}" readonly>
            <script>
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
            <input type="text" class="form-control bg-white" id="custgender" name="custgender" value="{{ $customer->custgender }}" readonly>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var custGenderInput = document.getElementById('custgender');
                    var custGenderValue = "{{ optional($customer)->custgender }}";
                    
                    if (custGenderValue === null) {
                        custGenderInput.value = '-';
                    } else if (custGenderValue === 'M') {
                        custGenderInput.value = 'Male';
                    } else if (custGenderValue === 'F') {
                        custGenderInput.value = 'Female';
                    }
                });
            </script>
            @error('custgender')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-12">
            <label for="custaddress">Address <span class="text-danger">*</span></label>
            <input type="text" class="form-control bg-white" id="custaddress" name="custaddress" value="{{ $customer->custaddress }}" readonly>
            <script>
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
    <!-- end: Customer Data -->
</div>
