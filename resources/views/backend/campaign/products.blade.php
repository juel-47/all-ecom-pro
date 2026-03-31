@extends('backend.layouts.master')
@section('title', 'Campaign Products')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Campaign Products</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Add Product to {{$campaign->name}}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.campaign.add-product') }}" method="POST">
                                @csrf
                                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Product</label>
                                            <select name="product_id" class="form-control select2">
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Discount Type</label>
                                            <select name="discount_type" class="form-control">
                                                <option value="percentage">Percentage (%)</option>
                                                <option value="amount">Amount (DKK)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Discount Value</label>
                                            <input type="text" name="discount_value" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">Add Product</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Campaign Products</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Discount Type</th>
                                            <th>Discount Value</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($campaignProducts as $index => $cp)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $cp->product->name }}</td>
                                                <td>{{ ucfirst($cp->discount_type) }}</td>
                                                <td>{{ $cp->discount_value }}</td>
                                                <td>
                                                    <a href="{{ route('admin.campaign.remove-product', $cp->id) }}" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if(count($campaignProducts) === 0)
                                            <tr>
                                                <td colspan="5" class="text-center">No products found in this campaign.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.select2').select2();
        })
    </script>
@endpush
