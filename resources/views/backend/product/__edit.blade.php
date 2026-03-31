@extends('backend.layouts.master')
@section('title', 'Product')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Product</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Product</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.products.update', $product->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label>Product Name <code>*</code></label>
                                    <input type="text" class="form-control" name="name" value="{{ $product->name }}"
                                        required>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="inputState">Category <code>*</code> </label>
                                        <select id="inputState" class="form-control main-category" name="category" required>
                                            <option value="">select category</option>
                                            @foreach ($categories as $category)
                                                <option {{ $category->id == $product->category_id ? 'selected' : '' }}
                                                    value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputState">Sub Category</label>
                                        <select id="inputState" class="form-control sub-category" name="sub_category">
                                            <option value="">select</option>
                                            @foreach ($subCategories as $subCategory)
                                                <option
                                                    {{ $subCategory->id == $product->sub_category_id ? 'selected' : '' }}
                                                    value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputState">Child Category</label>
                                        <select id="inputState" class="form-control child-category" name="child_category">
                                            <option value="">select</option>
                                            @foreach ($childCategories as $childCategory)
                                                <option
                                                    {{ $childCategory->id == $product->child_category_id ? 'selected' : '' }}
                                                    value="{{ $childCategory->id }}">{{ $childCategory->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Brand</label>
                                        <select id="inputState" class="form-control" name="brand">
                                            <option value="">Brands</option>
                                            @foreach ($brands as $brand)
                                                <option {{ $brand->id == $product->brand_id ? 'selected' : '' }}
                                                    value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>SKU</label>
                                        <input type="text" class="form-control" name="sku"
                                            value="{{ $product->sku }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Purchase Price <code>*</code></label>
                                        <input type="text" class="form-control" name="purchase_price"
                                            value="{{ $product->purchase_price }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Price <code>*</code></label>
                                        <input type="text" class="form-control" name="price"
                                            value="{{ $product->price }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Offer Price</label>
                                        <input type="text" class="form-control" name="offer_price"
                                            value="{{ $product->offer_price }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Offer Start Date</label>
                                        <input type="text" class="form-control datepicker" name="offer_start_date"
                                            value="{{ $product->offer_start_date }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Offer End Date</label>
                                        <input type="text" class="form-control datepicker" name="offer_end_date"
                                            value="{{ $product->offer_end_date }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Stock Quantity</label>
                                        <input type="number" min="0" class="form-control" name="qty"
                                            value="{{ $product->qty }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Video Link </label>
                                        <input type="text" class="form-control" name="video_link"
                                            value="{{ $product->video_link }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Short Description <code>*</code></label>
                                    <textarea name="short_description" class="form-control" required>{!! $product->short_description !!}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Long Description <code>*</code></label>
                                    <textarea name="long_description" class="form-control summernote" required> {!! $product->long_description !!}</textarea>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Product Type</label>
                                        <select id="inputState" class="form-control" name="product_type">
                                            <option value="">select</option>
                                            <option {{ $product->product_type == 'new_arrival' ? 'selected' : '' }}
                                                value="new_arrival">New Arrival</option>
                                            <option {{ $product->product_type == 'featured_product' ? 'selected' : '' }}
                                                value="featured_product">Featured</option>
                                            <option {{ $product->product_type == 'top_product' ? 'selected' : '' }}
                                                value="top_product">Top Product</option>
                                            <option {{ $product->product_type == 'best_product' ? 'selected' : '' }}
                                                value="best_product">Best Product</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Meta Title</label>
                                        <input type="text" class="form-control" name="meta_title"
                                            value="{{ $product->meta_title }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Meta Description</label>
                                    <textarea name="meta_description" class="form-control">{!! $product->meta_description !!}</textarea>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mr-5">Preview</label>
                                        <img src="{{ asset($product->thumb_image) }}" alt="" width="150px">
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option {{ $product->status == 1 ? 'selected' : '' }} value="1">Active
                                            </option>
                                            <option {{ $product->status == 0 ? 'selected' : '' }} value="0">Inactive
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Image alt text</label>
                                        <input type="text" class="form-control" name="image_alt_text">
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="form-group col-md-6">
                                        <label for="color">Color</label>
                                        <select class="form-control select2" name="proColor[]" multiple>
                                            <option value="">Select Color</option>
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->id }}" {{ $product->colors->contains($color->id) ? 'selected' : ''}}>
                                                    {{ $color->color_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="size">Size</label>
                                        <select class="form-control select2" name="proSize[]" multiple>
                                            <option value="">Select Size</option>
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size->id }}" {{ $product->sizes->contains($size->id) ? 'selected' : ''}}>{{ $size->size_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').on('change', '.main-category', function(e) {
                $('.child-category').html('<option value="">Select child Category</option>');
                let id = $(this).val();
                $.ajax({
                    url: "{{ route('admin.product.get-sub-categories') }}",
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $('.sub-category').html(
                            '<option value="">Select Sub Category</option>');
                        $.each(data, function(i, item) {
                            $('.sub-category').append(
                                `<option value="${item.id}">${item.name}</option>`)
                        })
                    },
                    error: function(xhr, status, error) {
                        console.error('Error status:', status);
                        console.error('Error message:', error);
                        console.error('XHR object:', xhr);
                    }
                });
            })

            /** get child categories */

            $('body').on('change', '.sub-category', function(e) {
                let id = $(this).val();
                $.ajax({
                    url: "{{ route('admin.product.get-child-categories') }}",
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $('.child-category').html(
                            '<option value="">Select child Category</option>');
                        $.each(data, function(i, item) {
                            $('.child-category').append(
                                `<option value="${item.id}">${item.name}</option>`)
                        })
                    },
                    error: function(xhr, status, error) {
                        console.error('Error status:', status);
                        console.error('Error message:', error);
                        console.error('XHR object:', xhr);
                    }
                });
            })
        })
    </script>
@endpush
