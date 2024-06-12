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
            @if (session()->has('error'))
                <div class="alert text-white bg-danger" role="alert">
                    <div class="iq-alert-text">{{ session('success') }}</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Product List</h4>
                </div>
                <div>
                <a href="{{ route('products.importView') }}" class="btn btn-danger add-list">Import</a>
                <a href="{{ route('products.exportData') }}" class="btn btn-danger add-list">Export</a>
                <a href="{{ route('products.create') }}" class="btn btn-primary add-list">Add Product</a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <form id="searchForm" action="{{ route('products.index') }}" method="get">
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
                        <label class="control-label col-sm-4 align-self-center" for="search"></label>
                        <div class="input-group col-sm-8">
                            <input type="text" id="search" class="form-control" name="search" placeholder="Search product" value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text bg-primary"><i class="fa-solid fa-magnifying-glass font-size-20"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive rounded">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No.</th>
                            <th>Photo</th>
                            <th>@sortablelink('productid', 'ID')</th>
                            <th>@sortablelink('productname', 'name')</th>
                            <th>@sortablelink('category.name', 'category')</th>
                            <th>@sortablelink('price', 'prices')</th>
                            <th>@sortablelink('stock', 'stocks')</th>
                            <th>@sortablelink('description', 'descriptions')</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($products as $product)
                        <tr>
                            <td>{{ (($products->currentPage() * 10) - 10) + $loop->iteration  }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $product->product_image ? asset('storage/products/'.$product->product_image) : asset('assets/images/product/default.webp') }}">
                            </td>
                            <td>{{ $product->productid }}</td>
                            <td>{{ $product->productname }}</td>
                            <td>{{ optional($product->category)->name ?? 'No category' }}</td>
                            <td> Rp {{ number_format( $product->price , 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @php
                                    $maxWords = 6; 
                                    $description = $product->description;
                                    $descriptionWords = explode(' ', $description);
                                    $truncatedDescription = implode(' ', array_slice($descriptionWords, 0, $maxWords));
                                    if (count($descriptionWords) > $maxWords) {
                                        $truncatedDescription .= '...';
                                    }
                                @endphp
                                {{ $truncatedDescription }}
                            </td>

                            <td>
                                <form action="{{ route('products.destroy', $product->productid) }}" method="POST" style="margin-bottom: 5px">
                                    @method('delete')
                                    @csrf
                                    <div class="d-flex align-items-center list-action">
                                        <a class="btn btn-info mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"
                                            href="{{ route('products.show', $product->productid) }}"><i class="ri-eye-line mr-0"></i>
                                        </a>
                                        <a class="btn btn-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                            href="{{ route('products.edit', $product->productid) }}""><i class="ri-pencil-line mr-0"></i>
                                        </a>
                                            <button type="submit" class="btn btn-warning mr-2 border-none" onclick="return confirm('Are you sure you want to delete this record?')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="ri-delete-bin-line mr-0"></i></button>
                                    </div>
                                </form>
                            </td>
                        </tr>

                        @empty
                        <div class="alert text-white bg-danger" role="alert">
                            <div class="iq-alert-text">Data not Found.</div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="ri-close-line"></i>
                            </button>
                        </div>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-lg-12 mb-5">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0">
                        <!-- Tabel disini -->
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        1 - {{ $products->count() }} dari {{ $products->total() }} hasil
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            1 dari {{ $products->lastPage() }} halaman
                        </div>
                        <div class="ml-3">
                            <a href="{{ $products->previousPageUrl() ?? '#' }}" class="btn btn-danger-{{ $products->previousPageUrl() ? 'primary' : 'secondary' }}">Sebelumnya</a>
                            <a href="{{ $products->nextPageUrl() ?? '#' }}" class="btn btn-danger-{{ $products->nextPageUrl() ? 'primary' : 'secondary' }} ml-2">Selanjutnya</a>
                        </div>
                    </div>
                </div>
            </div>
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
