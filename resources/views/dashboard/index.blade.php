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
        </div>
        <div class="col-lg-2 d-flex align-items-center">
            <div class="card card-transparent card-block card-stretch card-height border-none w-100">
                <div class="card-body p-0 mt-lg-2 mt-0 d-flex align-items-center">
                    <h3 class="mt-0">Hello <span style="color: #C11D38;">{{ auth()->user()->name }}</span>!</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-info-light">
                                    <img src="../assets/images/product/money.png" class="img-fluid" alt="image">
                                </div>
                                <div>
                                    <p class="mb-2">Total Paid</p>
                                    <h4>Rp {{ number_format( $total_paid , 0, ',', '.') }}</h4>
                                </div>
                            </div>
                            <div class="iq-progress-bar mt-2">
                                <span class="bg-info iq-progress progress-1" data-percent="85">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-warning-light">
                                    <img src="../assets/images/product/checklist.png" class="img-fluid" alt="image">
                                </div>
                                <div>
                                    <p class="mb-2">Pending Online Orders</p>
                                    <h4>{{ $total_pending }}</h4>
                                </div>
                            </div>
                            <div class="iq-progress-bar mt-2">
                                <span class="bg-warning iq-progress progress-1" data-percent="70">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-success-light">
                                    <img src="../assets/images/product/bags.png" class="img-fluid" alt="image">
                                </div>
                                <div>
                                    <p class="mb-2">Total Transactions</p>
                                    <h4>{{ $total_orders }}</h4>
                                </div>
                            </div>
                            <div class="iq-progress-bar mt-2">
                                <span class="bg-success iq-progress progress-1" data-percent="75">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-skyblue-light">
                                    <img src="../assets/images/product/box.png" class="img-fluid" alt="image">
                                </div>
                                <div>
                                    <p class="mb-2">Pending Supplier Orders</p>
                                    <h4>{{ $total_pendingorder }}</h4>
                                </div>
                            </div>
                            <div class="iq-progress-bar mt-2">
                                <span class="bg-skyblue iq-progress progress-1" data-percent="25">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Profit Overview</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <div class="dropdown">
                            <span class="dropdown-toggle dropdown-bg btn" id="dropdownMenuButton002"
                                data-toggle="dropdown">
                                @if ($selectedPeriod === 'year')
                                    Yearly
                                @elseif ($selectedPeriod === 'month')
                                    Monthly
                                @elseif ($selectedPeriod === 'week')
                                    Daily
                                @endif
                                <i class="ri-arrow-down-s-line ml-1"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right shadow-none"
                                aria-labelledby="dropdownMenuButton002">
                                <a class="dropdown-item" href="{{ route('dashboard', ['period' => 'year']) }}">Yearly</a>
                                <a class="dropdown-item" href="{{ route('dashboard', ['period' => 'month']) }}">Monthly</a>
                                <a class="dropdown-item" href="{{ route('dashboard', ['period' => 'week']) }}">Daily</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div id="profit-chart" style="min-height: 360px;"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Sales Overview</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <div class="dropdown">
                            <span class="dropdown-toggle dropdown-bg btn" id="dropdownMenuButton002"
                                data-toggle="dropdown">
                                @if ($selectedPeriod === 'year')
                                    Yearly
                                @elseif ($selectedPeriod === 'month')
                                    Monthly
                                @elseif ($selectedPeriod === 'week')
                                    Daily
                                @endif
                                <i class="ri-arrow-down-s-line ml-1"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right shadow-none"
                                aria-labelledby="dropdownMenuButton002">
                                <a class="dropdown-item" href="{{ route('dashboard', ['period' => 'year']) }}">Yearly</a>
                                <a class="dropdown-item" href="{{ route('dashboard', ['period' => 'month']) }}">Monthly</a>
                                <a class="dropdown-item" href="{{ route('dashboard', ['period' => 'week']) }}">Daily</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="transactionsProfitChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Top Products Sold</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <div class="dropdown">
                            <span class="dropdown-toggle dropdown-bg btn" id="dropdownMenuButton006" data-toggle="dropdown">
                                @if ($selectedPeriod === 'year')
                                    This Year
                                @elseif ($selectedPeriod === 'month')
                                    This Month
                                @elseif ($selectedPeriod === 'week')
                                    This Week
                                @endif
                                <i class="ri-arrow-down-s-line ml-1"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right shadow-none" aria-labelledby="dropdownMenuButton006">
                                <a class="dropdown-item" href="{{ route('dashboard', ['period' => 'year']) }}">Year</a>
                                <a class="dropdown-item" href="{{ route('dashboard', ['period' => 'month']) }}">Month</a>
                                <a class="dropdown-item" href="{{ route('dashboard', ['period' => 'week']) }}">Week</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled row top-product mb-0">
                        @foreach ($data as $product)
                            <li class="col-lg-3">
                                <div class="card card-block card-stretch card-height mb-0">
                                    <div class="card-body">
                                        <div class="bg-warning-light rounded">
                                            <img src="{{ $product->product_image ? asset('storage/products/'.$product->product_image) : asset('assets/images/product/default.webp') }}" class="style-img img-fluid m-auto p-3" alt="image">
                                        </div>
                                        <div class="style-text text-left mt-3">
                                            <h5 class="mb-1">{{ $product->productname }}</h5>
                                            <p class="mb-0">Sold: {{ $product->quantity_sold }} Item</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card card-transparent card-block card-stretch mb-4">
                <div class="card-header d-flex align-items-center justify-content-between p-0">
                    <div class="header-title">
                        <h4 class="card-title mb-0">New Products</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <div><a href="#" class="btn btn-primary view-btn font-size-14">View All</a></div>
                    </div>
                </div>
            </div>
            @foreach ($new_products as $product)
            <div class="card card-block card-stretch card-height-helf">
                <div class="card-body card-item-right">
                    <div class="d-flex align-items-top">
                        <div class="bg-warning-light rounded">
                            <img src="{{ $product->product_image ? asset('storage/products/'.$product->product_image) : asset('assets/images/product/default.webp') }}" class="style-img img-fluid m-auto p-3" style="max-width: 200px; max-height: 200px;" alt="image">
                        </div>                        
                        <div class="style-text text-left">
                            <h5 class="mb-2">{{ $product->productname }}</h5>
                            <p class="mb-2">Stock : {{ $product->stock }}</p>
                            <p class="mb-0">Price : Rp{{ number_format($product->price  , 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Page end  -->
</div>

<style>
    #profit-chart {
        border-radius: 10px; /* Atur nilai radius sesuai keinginan */
    }
</style>
@endsection

@section('specificpagescripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var profitData = {!! json_encode($profitData) !!};

        // ApexCharts script for profit overview
        var options = {
            chart: {
                type: 'line',
                height: 360,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Profit',
                data: profitData.map(item => item.profit)
            }],
            xaxis: {
                type: 'datetime',
                categories: profitData.map(item => new Date(item.date).getTime()),
                labels: {
                    datetimeFormatter: {
                        year: 'yyyy',
                        month: 'MMM \'yy',
                        day: 'dd MMM',
                    }
                }
            },
            markers: {
                size: 6,
                colors: ['#C11D38'],
                strokeColors: ['#fff'],
                strokeWidth: 2,
                strokeOpacity: 0.9,
                hover: {
                    size: 8
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            stroke: {
                width: 1,
                colors: ['#C11D38']
            }
        };

        var chart = new ApexCharts(document.querySelector("#profit-chart"), options);
        chart.render();

        // Chart.js script for Sales Overview
        var transactionsProfitData = {
            daily: {!! json_encode($dailyProfits) !!},
            monthly: {!! json_encode($monthlyProfits) !!},
            yearly: {!! json_encode($yearlyProfits) !!}
        };

        function createTransactionsProfitChart(data, label) {
            new Chart(document.getElementById('transactionsProfitChart'), {
                type: 'line',
                data: {
                    labels: data.map(item => item.date),
                    datasets: [{
                        label: label,
                        data: data.map(item => item.total),
                        backgroundColor: 'rgba(193, 29, 56, 0.2)',
                        borderColor: 'rgba(131, 22, 40, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        var selectedPeriod = '{{ $selectedPeriod }}';
        if (selectedPeriod === 'year') {
            createTransactionsProfitChart(transactionsProfitData.yearly, 'Yearly Sales Overview');
        } else if (selectedPeriod === 'month') {
            createTransactionsProfitChart(transactionsProfitData.monthly, 'Monthly Sales Overview');
        } else if (selectedPeriod === 'week') {
            createTransactionsProfitChart(transactionsProfitData.daily, 'Daily Sales Overview');
        }
    });
</script>
@endsection