@extends('admin.layouts.app')

@section('title')
    Product
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Product',
        'page' => 'Product',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <style>
                /* Compact stats: reduce column gutters, card padding and number size */
                .compact-stats {
                    margin-left: -6px;
                    margin-right: -6px;
                }

                .compact-stats>.col-md-3,
                .compact-stats>.col-sm-6 {
                    padding-left: 6px;
                    padding-right: 6px;
                }

                .compact-stats .small-box {
                    padding: .5rem 0.6rem;
                    margin-bottom: 8px;
                }

                /* Slightly smaller stat numbers for compact view */
                .compact-stats .small-box .inner h5 {
                    font-size: 1rem;
                    /* ~16px */
                    margin-bottom: 0.25rem;
                }

                /* Make stat cards visually clickable */
                .small-box.clickable {
                    cursor: pointer;
                }

                /* Tighten the small label spacing */
                .compact-stats .small-box .inner small {
                    display: block;
                    line-height: 1;
                    font-size: .75rem;
                }
            </style>

            <div class="row compact-stats">
                <div class="col-md-4 col-12">
                    <a href="{{ route('admin.product.index') }}"
                        class="small-box bg-primary p-3 text-white clickable d-block">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="inner text-center">
                                <h5 class="mb-0 font-weight-bold">{{ $totalProductCount }}</h5>
                                <small class="text-uppercase font-weight-bold" style="letter-spacing: 0.5px;">Total
                                    Product</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-12">
                    <a href="{{ route('admin.order.index', array_merge(request()->except('page'), ['filter' => 'total_orders'])) }}"
                        class="small-box bg-info p-3 text-white clickable d-block">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="inner text-center">
                                <h5 class="mb-0 font-weight-bold">{{ $totalProductUnit }}</h5>
                                <small class="text-uppercase font-weight-bold" style="letter-spacing: 0.5px;">Total
                                    Unit</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-12">
                    <a href="{{ route('admin.order.index', array_merge(request()->except('page'), ['filter' => 'total_orders'])) }}"
                        class="small-box bg-success p-3 text-white clickable d-block">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="inner text-center">
                                <h5 class="mb-0 font-weight-bold">{{ number_format($totalProductAmount, 2) }}</h5>
                                <small class="text-uppercase font-weight-bold" style="letter-spacing: 0.5px;">Total
                                    Amount</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Product
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <form action="{{ route('admin.product.index') }}" class="input-group input-group-sm mr-3"
                                    style="width: 150px;">
                                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                                        class="form-control float-right" placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                                <a href="{{ route('admin.product.create') }}" class="btn btn-primary">
                                    Create
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th>Picture</th>
                                        <th>Name</th>
                                        {{-- <th>Brand</th> --}}
                                        <th>Category</th>
                                        <th>Buying price</th>
                                        <th>Selling Price</th>
                                        <th>Initial Stock</th>
                                        {{-- <th>
                                            Flash Deal
                                        </th>
                                        <th>Status</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $index => $product)
                                        <tr>
                                            <td>
                                                {{ $products->firstItem() + $index }}
                                            </td>
                                            <td>
                                                <img class="rounded img-fluid" width="50" height="50"
                                                    src="{{ $product->picture_url }}" alt="{{ $product->name }}">
                                            </td>
                                            <td>
                                                <div class="mt-1 text-truncate" style="max-width: 350px;">
                                                    {{ Str::limit($product->name, 70) }}
                                                </div>
                                                <span class="text-muted" style="font-size: 14px">
                                                    {{ $product->sku }}
                                                </span>
                                            </td>
                                            {{-- <td>
                                                <a href="{{ route('admin.brand.index', ['keyword' => $product->brand?->name]) }}"
                                                    class="font-weight-bold">
                                                    {{ $product->brand?->name }}
                                                </a>
                                            </td> --}}
                                            <td>
                                                <a href="{{ route('admin.category.index', ['keyword' => $product->category->name]) }}"
                                                    class="font-weight-bold">
                                                    {{ $product->category->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold">
                                                    {{ showAmount($product->buying_price) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold">
                                                    {{ showAmount($product->selling_price) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                            </td>
                                            {{-- <td>
                                                <form id="offerForm{{ $product->id }}"
                                                    action="{{ route('admin.product.offer.update', $product->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="icheck-success d-inline">
                                                        <input name="offer" @checked($product->show_special_offer_list) value="1"
                                                            type="checkbox" id="offer{{ $product->id }}"
                                                            onchange="$('#offerForm{{ $product->id }}').submit()">
                                                        <label for="offer{{ $product->id }}"></label>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>
                                                @if ($product->is_active)
                                                    <span class="badge badge-success">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        Disabled
                                                    </span>
                                                @endif
                                            </td> --}}
                                            <td>
                                                <div class="d-flex items-align-center">
                                                    <a href="{{ route('admin.gallery.index') . '?product=' . $product->id }}"
                                                        class="btn btn-secondary btn-outline mr-2">
                                                        <i class="fas fa-image"></i> Gallery
                                                    </a>
                                                    <a href="{{ route('admin.product.show', $product->id) }}"
                                                        class="btn btn-info mr-2">
                                                        <i class="fas fa-eye"></i> Variation
                                                    </a>
                                                    <a href="{{ route('admin.product.edit', $product->id) }}"
                                                        class="btn btn-info mr-2">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('admin.product.destroy', $product->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Are you sure?')"
                                                            class="btn btn-danger">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">
                                                No Data ....
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $products->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
