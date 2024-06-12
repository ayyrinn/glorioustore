@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-block">
                <div class="card-header d-flex justify-content-between bg-primary">
                    <div class="iq-header-title">
                        <h4 class="card-title mb-0">Invoice</h4>
                    </div>

                    <div class="invoice-btn d-flex">
                        <form action="{{ route('pos.printInvoice') }}" method="post">
                            @csrf
                            <input type="hidden" name="customerid" value="{{ $customer->customerid }}">
                            <button type="submit" class="btn btn-primary-dark mr-2"><i class="las la-print"></i> Print</button>
                        </form>

                        <button type="button" class="btn btn-primary-dark mr-2" data-toggle="modal" data-target=".bd-example-modal-lg">Create</button>

                        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-white">
                                        <h3 class="modal-title text-center mx-auto">Invoice of {{ $customer->custname }}<br/>Total Amount Rp{{ Cart::instance('pos')->total() }}</h3>
                                    </div>
                                    <form id="transactionForm" action="{{ route('pos.storeTransaction') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="customerid" value="{{ $customer->customerid }}">
                                            <input type="hidden" name="employeeid" value="{{ $employee->employeeid }}">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="payment">Payment</label>
                                                    <select class="form-control @error('payment') is-invalid @enderror" id="payment" name="payment">
                                                        <option selected="" disabled="">-- Select Payment --</option>
                                                        <option value="CASH">Cash</option>
                                                        <option value="DEBIT">Debit</option>
                                                        <option value="OTHER">Other</option>
                                                    </select>
                                                    @error('payment')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="pay">Pay Now</label>
                                                    <input type="number" class="form-control @error('pay') is-invalid @enderror" id="pay" name="pay" value="{{ old('pay') }}">
                                                    @error('pay')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12 d-none" id="cashPaymentSection">
                                                <div class="form-group">
                                                    <label for="change">Change</label>
                                                    <input type="number" class="form-control" id="change" name="change" readonly>
                                                </div>
                                                <button type="button" class="btn btn-secondary col-md-12" id="calculateChange">Calculate Change</button>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <img src="{{ asset('assets/images/logo.png') }}" class="logo-invoice img-fluid mb-3">
                            <h5 class="mb-3">Hello, {{ $customer->custname }}</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive-sm">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Order Date</th>
                                            <th scope="col">Customer Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ Carbon\Carbon::now()->format('M d, Y') }}</td>
                                            <p>Cashier: {{ $employee ? $employee->name : '-' }}</p>
                                            <td>
                                                <p class="mb-0">{{ $customer->custname }}<br>
                                                    Phone: {{ $customer->custnum }}<br>
                                                    Points: {{ $customer->points }}<br>
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="mb-3">Order Summary</h5>
                            <div class="table-responsive-lg">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">#</th>
                                            <th scope="col">Item</th>
                                            <th class="text-center" scope="col">Quantity</th>
                                            <th class="text-center" scope="col">Price</th>
                                            <th class="text-center" scope="col">Totals</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($content as $item)
                                        <tr>
                                            <th class="text-center" scope="row">{{ $loop->iteration }}</th>
                                            <td>
                                                <h6 class="mb-0">{{ $item->name }}</h6>
                                            </td>
                                            <td class="text-center">{{ $item->qty }}</td>
                                            <td class="text-center">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="text-center"><b>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</b></td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 mb-3">
                        <div class="offset-lg-8 col-lg-4">
                            <div class="or-detail rounded">
                                <div class="p-3">
                                    <h5 class="mb-3">Order Details</h5>
                                    <div class="mb-2">
                                        <h6>Sub Total</h6>
                                        <p>Rp{{ number_format(Cart::instance('pos')->subtotal(), 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="ttl-amt py-2 px-3 d-flex justify-content-between align-items-center">
                                    <h6>Total</h6>
                                    <h3 class="text-primary font-weight-700">Rp{{ number_format(Cart::instance('pos')->total(), 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentSelect = document.getElementById('payment');
        const cashPaymentSection = document.getElementById('cashPaymentSection');
        const calculateChangeButton = document.getElementById('calculateChange');
        const payInput = document.getElementById('pay');
        const changeInput = document.getElementById('change');
        const totalAmount = {{ Cart::instance('pos')->total() }};
    
        paymentSelect.addEventListener('change', function () {
            if (paymentSelect.value === 'CASH') {
                cashPaymentSection.classList.remove('d-none');
            } else {
                cashPaymentSection.classList.add('d-none');
            }
        });
    
        calculateChangeButton.addEventListener('click', function () {
            const cashPaid = parseFloat(payInput.value);
            if (!isNaN(cashPaid)) {
                const change = cashPaid - totalAmount;
                changeInput.value = change >= 0 ? change : 0;
            }
        });
    });
</script>
@endsection
