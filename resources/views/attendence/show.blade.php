@extends('dashboard.body.main')

@section('specificpagestyles')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Attendance List</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('attendence.store') }}" method="POST">
                    @csrf
                        <!-- begin: Input Data -->
                        <div class="row align-items-center">
                            <div class="form-group col-md-4">
                                <label for="datepicker">Date</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-white" value="{{ $date }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="table-responsive rounded mb-3">
                                    <table class="table mb-0">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th>No.</th>
                                                <th>Employee</th>
                                                <th class="text-center">Attendence Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach ($attendences as $key => $attendence)
                                            <tr>
                                                <td>{{ $key + 1 }}</td> 
                                                <td>{{ $attendence->employee->name}}</td>
                                                <td class="text-uppercase">
                                                    @php
                                                        $badgeClass = ($attendence->status === 'present') ? 'badge-success' : 'badge-danger'; // Determine badge class based on status
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ $attendence->status }}</span>
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- end: Input Data -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>

<script>
    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd'
        // https://gijgo.com/datetimepicker/configuration/format
    });
</script>
@endsection
