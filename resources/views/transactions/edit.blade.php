@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">

        <!-- Display Transaction Information -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Information Transaction Details</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Display Transaction Information -->
                        <div class="form-group col-md-6">
                            <label>Transaction Invoice</label>
                            <input class="form-control bg-white" value="{{ $onlinetransaction->transactionid }}" readonly/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Transaction Date</label>
                            <input type="text" class="form-control bg-white" value="{{ $onlinetransaction->transaction->date }}" readonly>
                        </div>

                        <!-- Cashier Name Dropdown -->
                        <div class="form-group col-md-6">
                            <form id="updateFormCashier" action="{{ route('transaction.updateOnlineCashier') }}" method="POST">
                                @csrf
                                <input type="hidden" name="transactionid" value="{{ $transaction->transactionid }}">
                                <label for="employeeid">Cashier Name</label>
                                <select class="form-control" id="employeeid" name="employeeid">
                                    @foreach($employees as $employee)
                                        @if($employee->role === 'KASIR')
                                            <option value="{{ $employee->employeeid }}" {{ old('employeeid', $transaction->employeeid) == $employee->employeeid ? 'selected' : '' }}>{{ $employee->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        <!-- Courier Name Dropdown -->
                        <div class="form-group col-md-6">
                            <form id="updateFormCourier" action="{{ route('transaction.updateOnlineCourier') }}" method="POST">
                                @csrf
                                <input type="hidden" name="transactionid" value="{{ $onlinetransaction->transactionid }}">
                                <label for="courierid">Courier Name</label>
                                <select class="form-control" id="courierid" name="courierid">
                                    <option value="">--Select Courier--</option>
                                    @foreach($employees as $employee)
                                        @if($employee->role === 'KURIR')
                                        <option value="{{ $employee->employeeid }}" {{ old('courierid', $onlinetransaction->courierid) == $employee->employeeid ? 'selected' : '' }}>{{ $employee->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        <!-- Other Transaction Details -->
                        <div class="form-group col-md-6">
                            <label>Payment Method</label>
                            <input class="form-control bg-white" value="{{ $onlinetransaction->transaction->payment }}" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Status</label>
                            <input class="form-control bg-white" value="{{ $onlinetransaction->status }}" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Customer Name</label>
                            <input type="text" class="form-control bg-white" value="{{ $onlinetransaction->transaction->customer->custname }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Customer Phone</label>
                            <input type="text" class="form-control bg-white" value="{{ $onlinetransaction->transaction->customer->custnum }}" readonly>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Customer Address</label>
                            <input type="text" class="form-control bg-white" value="{{ $onlinetransaction->transaction->customer->custaddress }}" readonly>
                        </div>

                    </div>

                    <div class="row">
                        @if($onlinetransaction->status != 'BELUM BAYAR')
                        <!-- Tombol Payment Proof -->
                        <div class="col-lg-2">
                            <div class="d-flex align-items-center list-action">
                                <button type="button" class="btn btn-primary mr-2 border-none" data-toggle="modal" data-target="#paymentProofModal" data-original-title="Payment Proof">Payment Proof</button>
                            </div>
                        </div>
                        @endif
                        <!-- Action Buttons -->
                        @if($onlinetransaction->courierid && $onlinetransaction->status != 'DIKIRIM' && $onlinetransaction->status != 'DITERIMA' && $onlinetransaction->status != 'DIBATALKAN')
                        <div class="col-lg-2">
                            <div class="d-flex align-items-right list-action">
                                <form action="{{ route('transaction.updateStatusOnline') }}" method="POST" style="margin-bottom: 5px">
                                    @method('put')
                                    @csrf
                                    <input type="hidden" name="transactionid" value="{{ $onlinetransaction->transactionid }}">
                                    <input type="hidden" name="status" value="DIKIRIM">
                                    <button type="submit" class="btn btn-success mr-2 border-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Complete">Send Order</button>
                                </form>
                            </div>
                        </div>
                        @endif
                        @if($onlinetransaction->status == 'DIKIRIM')
                        <!-- Tombol Complete Order -->
                        <div class="col-lg-2">
                            <div class="d-flex align-items-center list-action">
                                <button type="button" class="btn btn-success mr-2 border-none" data-toggle="modal" data-target="#proofDeliveryModal" data-original-title="Complete">Complete Order</button>
                            </div>
                        </div>
                        @endif
                        @if($onlinetransaction->status == 'DITERIMA')
                        <!-- Tombol Delivery Proof -->
                        <div class="col-lg-2">
                            <div class="d-flex align-items-center list-action">
                                <button type="button" class="btn btn-primary mr-2 border-none" data-toggle="modal" data-target="#deliveryProofModal" data-original-title="Delivery Proof">Delivery Proof</button>
                            </div>
                        </div>
                        @endif
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="paymentProofModal" tabindex="-1" role="dialog" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content align-items-center">
                                <div class="modal-header bg-white">
                                    <h3 class="modal-title text-center mx-auto" id="paymentProofModalLabel">Payment Proof<br/>#{{ $onlinetransaction->transactionid }}</h3>
                                </div>
                                <div class="modal-body">
                                    <img class="img-fluid" id="payment-proof-image-preview" src="{{asset('storage/transaction/payment/' . $onlinetransaction->proofpayment)}}" alt="Delivery Proof">
                                </div>
                                <div class="modal-footer">
                                    @if($onlinetransaction->status !== 'DITERIMA')                                
                                    <form action="{{ route('transaction.cancelStatusOnline') }}" method="POST" style="margin-bottom: 5px">
                                        @method('put')
                                        @csrf
                                        <input type="hidden" name="transactionid" value="{{ $onlinetransaction->transactionid }}">
                                        <input type="hidden" name="status" value="DIBATALKAN">
                                        <button type="submit" class="btn btn-outline-white">Cancel Order</button>
                                    </form>
                                    @endif
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="proofDeliveryModal" tabindex="-1" role="dialog" aria-labelledby="proofDeliveryModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-white">
                                    <h3 class="modal-title text-center mx-auto" id="proofDeliveryModalLabel">Proof of Delivery<br/>#{{ $onlinetransaction->transactionid }}</h3>
                                </div>
                                <form action="{{ route('transaction.completeOnline') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="transactionid" value="{{ $onlinetransaction->transactionid }}">
                                        <input type="hidden" name="status" value="DITERIMA">

                                        <!-- begin: Input Image -->
                                        <div class="form-group row align-items-center">
                                            <div class="col-md-12">
                                                <div class="profile-img-edit">
                                                    <div class="crm-profile-img-edit">
                                                        <img class="crm-profile-pic rounded-circle avatar-100" id="delivery-proof-image-preview" src="{{ asset('assets/images/product/default.webp') }}" alt="profile-pic">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-4 col-lg-12">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('proofdelivery') is-invalid @enderror" id="proofdelivery" name="proofdelivery" accept="image/*" onchange="previewImage();">
                                                <label class="custom-file-label" for="proofdelivery">Choose file</label>
                                            </div>
                                            @error('proofdelivery')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="deliveryProofModal" tabindex="-1" role="dialog" aria-labelledby="deliveryProofModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content align-items-center">
                                <div class="modal-header bg-white">
                                    <h3 class="modal-title text-center mx-auto" id="deliveryProofModalLabel">Delivery Proof<br/>#{{ $onlinetransaction->transactionid }}</h3>
                                </div>
                                <div class="modal-body">
                                    <img class="img-fluid" id="image-preview" src="{{asset('storage/transaction/delivery/' . $onlinetransaction->proofdelivery)}}" alt="Delivery Proof">
                                </div>                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                
            </div>
        </div>

        <!-- Display Products Information -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="light light-data">
                            <th>No.</th>
                            <th>Photo</th>
                            <th>Product Name</th>
                            <th>Product Code</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="light-body">
                        @foreach ($transactionDetails as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $item->product->product_image ? asset('storage/products/'.$item->product->product_image) : asset('assets/images/product/default.webp') }}">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menangani perubahan pada dropdown Cashier
        document.getElementById('employeeid').addEventListener('change', function() {
            document.getElementById('updateFormCashier').submit();
        });

        // Menangani perubahan pada dropdown Courier
        document.getElementById('courierid').addEventListener('change', function() {
            document.getElementById('updateFormCourier').submit();
        });
    });

    function previewImage(){
        const image = document.querySelector('#image');
        const imagePreview = document.querySelector('#image-preview');

        imagePreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent) {
            imagePreview.src = oFREvent.target.result;
        }
    }
    
    function previewImage(event){
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function(){
            const preview = document.getElementById('delivery-proof-image-preview');
            preview.src = reader.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>
@endsection
