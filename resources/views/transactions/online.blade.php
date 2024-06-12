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
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Online Transaction List</h4>
                </div>
                <div>
                    <a href="{{ route('transaction.online') }}" class="btn btn-danger add-list"><i class="fa-solid fa-trash mr-3"></i>Clear Search</a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <form id="searchForm" action="{{ route('transaction.online') }}" method="get">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="form-group row">
                        <label for="row" class="col-sm-3 align-self-center">Row:</label>
                        <div class="col-sm-9">
                            <select id="row" class="form-control" name="row">
                                <option value="10" @if(request('row') == '10')selected="selected"@endif>10</option>
                                <option value="25" @if(request('row') == '25')selected="selected"@endif>25</option>
                                <option value="50" @if(request('row') == '50')selected="selected"@endif>50</option>
                                <option value="100" @if(request('row') == '100')selected="selected"@endif>100</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-sm-3 align-self-center" for="search">Search:</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" id="search" class="form-control" name="search" placeholder="Search transaction" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text bg-primary"><i class="fa-solid fa-magnifying-glass font-size-20"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No.</th>
                            <th>ID</th>
                            <th>@sortablelink('date', 'date')</th>
                            <th>@sortablelink('employeename', 'courier')</th>
                            <th>@sortablelink('total')</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>@sortablelink('status')</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach ($onlinetransactions as $onlinetransaction)
                        <tr>
                            <td>{{ (($onlinetransactions->currentPage() * 10) - 10) + $loop->iteration  }}</td>
                            <td>{{ $onlinetransaction->transactionid }}</td>
                            <td>{{ $onlinetransaction->transaction->date }}</td>
                            <td>{{ $onlinetransaction->courierid }}</td>
                            <td>Rp{{ number_format($onlinetransaction->transaction->total, 0, ',', '.') }}</td>
                            <td>{{ $onlinetransaction->transaction->customer->custname }}</td>
                            <td>{{ $onlinetransaction->transaction->customer->custaddress }}</td>
                            {{-- <td>{{ $onlinetransaction->status }}</td> --}}
                            <td>
                                <span class="badge badge-success">{{ $onlinetransaction->status }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="btn btn-info mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" href="{{ route('transaction.editOnline', $onlinetransaction->transactionid) }}">
                                        Details
                                    </a>
                                    <a class="btn btn-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" href="{{ route('transaction.invoiceDownload', $onlinetransaction->transactionid) }}">
                                        Print
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $onlinetransactions->links() }}
        </div>
    </div>
    <!-- Page end  -->
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('row').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    });
</script>
@endsection
