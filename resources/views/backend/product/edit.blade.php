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
                                    {{-- <div class="form-group col-md-6">
                                        <label>Offer Start Date</label>
                                        <input type="text" class="form-control datepicker" name="offer_start_date"
                                            value="{{ $product->offer_start_date }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Offer End Date</label>
                                        <input type="text" class="form-control datepicker" name="offer_end_date"
                                            value="{{ $product->offer_end_date }}">
                                    </div> --}}
                                    <div class="form-group col-md-6">
                                        <label>Offer Start Date</label>
                                        <input type="datetime-local" name="offer_start_date" class="form-control"
                                            value="{{ $product->offer_start_date }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Offer End Date</label>
                                        <input type="datetime-local" name="offer_end_date" class="form-control"
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
                                    <textarea name="long_description" class="form-control summernote" required>{!! $product->long_description !!}</textarea>
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
                                        <input type="text" class="form-control" name="image_alt_text"
                                            value="{{ $product->image_alt_text }}">
                                    </div>
                                    <!-- Size, Color, Price Combinations -->
                                    <div class="form-group col-md-12">
                                        <label>Add Size and Price Combinations</label>
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <select class="form-control" id="variantSize" name="proSize[]">
                                                    <option value="">Select Size (Optional)</option>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}">{{ $size->size_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- <div class="col-md-4">
                                                <select class="form-control" id="variantColor" name="proColor[]">
                                                    <option value="">Select Color (Optional)</option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}">{{ $color->color_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            <div class="col-md-5">
                                                <input type="number" class="form-control" id="variantPrice"
                                                    placeholder="Price (Optional)" min="0" step="0.01"
                                                    name="variant_price">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-primary"
                                                    id="addVariant">Add</button>
                                            </div>
                                        </div>
                                        <div class="mt-3" id="variantList">
                                            @php
                                                $index = 0;
                                            @endphp
                                            @foreach ($product->sizes as $size)
                                                @php
                                                    $colorPivot = $product->colors()->first();
                                                    $price = $size->pivot->size_price;
                                                @endphp
                                                <div class="variant-item btn btn-primary"
                                                    data-index="{{ $index }}">
                                                    <div class="variant-content">
                                                        <span>Size: {{ $size->size_name }}, Price:
                                                            {{ $price }}</span>
                                                        <i class="fas fa-times remove-variant"></i>
                                                    </div>
                                                    <input type="hidden" name="variants[{{ $index }}][size_id]"
                                                        value="{{ $size->id }}">
                                                    <input type="hidden" name="variants[{{ $index }}][price]"
                                                        value="{{ $price }}">
                                                </div>
                                                @php $index++; @endphp
                                            @endforeach
                                            {{-- @foreach ($product->colors as $color)
                                                @php
                                                    $price = $color->pivot->color_price;
                                                @endphp
                                                <div class="variant-item btn btn-primary"
                                                    data-index="{{ $index }}">
                                                    <div class="variant-content">
                                                        <span>Color: {{ $color->color_name }}, Price:
                                                            {{ $price }}</span>
                                                        <i class="fas fa-times remove-variant"></i>
                                                    </div>
                                                    <input type="hidden" name="variants[{{ $index }}][color_id]"
                                                        value="{{ $color->id }}">
                                                    <input type="hidden" name="variants[{{ $index }}][price]"
                                                        value="{{ $price }}">
                                                </div>
                                                @php $index++; @endphp
                                            @endforeach --}}
                                        </div>
                                    </div>


                                </div>
                                {{-- <h5>Add Colors with Images</h5>
                                <div id="colorImageWrapper">
                                    <input type="hidden" name="deleted_color_ids" id="deleted_color_ids"
                                        value="">
                                    @foreach ($product->productImageGalleries as $index => $gallery)
                                        <div class="row mb-3 color-image-row" data-index="{{ $index }}">
                                            <div class="col-md-5">
                                                <select class="form-control" name="proColor[]">
                                                    <option value="">Select Color</option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}"
                                                            {{ $color->id == $gallery->color_id ? 'selected' : '' }}>
                                                            {{ $color->color_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="file" class="form-control" name="color_image[]">
                                                <img src="{{ asset($gallery->image) }}" width="50px" height="50px"
                                                    class="mt-2 rounded-circle">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button"
                                                    class="btn btn-danger removeColorRow">Remove</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-primary" id="addColorRow">Add More Color</button> --}}

                                <h5>Add Colors with Images</h5>
{{-- <div id="colorImageWrapper">
    <input type="hidden" name="deleted_color_ids" id="deleted_color_ids" value="">
    @foreach ($product->productImageGalleries as $index => $gallery)
    <div class="row mb-3 color-image-row" data-index="{{ $index }}">
        <div class="col-md-5">
            <select class="form-control" name="proColor[]" id="variantColor">
                <option value="">Select Color</option>
                @foreach ($colors as $color)
                    <option value="{{ $color->id }}" {{ $color->id == $gallery->color_id ? 'selected' : '' }}>
                        {{ $color->color_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control color-image-input" name="color_image[]">
            <img src="{{ asset($gallery->image) }}" width="50px" height="50px"
                class="mt-2 rounded-circle color-image-preview">
                @if($gallery->color_id && $gallery->image)
    <img src="{{ asset($gallery->image) }}" width="50px" height="50px"
        class="mt-2 rounded-circle color-image-preview">
@endif
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger removeColorRow">Remove</button>
        </div>
    </div>
    @endforeach
</div> --}}
<div id="colorImageWrapper">
    <input type="hidden" name="deleted_color_ids" id="deleted_color_ids" value="">
    
    @foreach ($product->productImageGalleries as $index => $gallery)
        @if($gallery->color_id)  <!-- Only render the entire row if color_id exists -->
            <div class="row mb-3 color-image-row" data-index="{{ $index }}">
                <div class="col-md-5">
                    <select class="form-control" name="proColor[]">
                        <option value="">Select Color</option>
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}"
                                {{ $color->id == $gallery->color_id ? 'selected' : '' }}>
                                {{ $color->color_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="file" class="form-control color-image-input" name="color_image[]">
                    
                    <!-- Show preview only if image exists -->
                    @if($gallery->image)
                        <img src="{{ asset($gallery->image) }}"
                             width="50px" height="50px"
                             class="mt-2 rounded-circle color-image-preview">
                    @else
                        <img src="" width="50px" height="50px"
                             class="mt-2 rounded-circle color-image-preview" style="display:none;">
                    @endif
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger removeColorRow">Remove</button>
                </div>
            </div>
        @endif
    @endforeach
</div>
<button type="button" class="btn btn-primary" id="addColorRow">Add More Color</button>

<hr>
<div class="mt-4">
    <button type="submit" class="btn btn-primary start-left">Update</button>
</div>
</form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <style>
        .variant-item {
            display: inline-block;
            /* Makes it behave like a button */
            background-color: #007bff;
            /* Matches btn-primary */
            color: #fff;
            /* Matches btn-primary text */
            padding: 6px 12px;
            /* Matches btn padding */
            margin: 0 10px 10px 0;
            /* Spacing between items */
            border-radius: 4px;
            /* Matches btn border-radius */
            border: 1px solid #0069d9;
            /* Matches btn-primary border */
            font-size: 16px;
            /* Matches btn font-size */
            font-weight: 400;
            /* Matches btn font-weight */
            line-height: 1.5;
            /* Matches btn line-height */
            text-align: center;
            transition: background-color 0.2s, border-color 0.2s;
            /* Matches btn hover transition */
            min-width: 200px;
            /* Ensures consistent width */
        }

        .variant-item:hover {
            background-color: #0069d9;
            /* Matches btn-primary hover */
            border-color: #0062cc;
            /* Matches btn-primary hover border */
        }

        .variant-item .variant-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .variant-item .remove-variant {
            color: #fff;
            background-color: #dc3545;
            /* Matches btn-danger */
            padding: 2px 8px;
            border-radius: 3px;
            margin-left: 10px;
            font-size: 14px;
            line-height: 1;
            cursor: pointer;
        }

        .variant-item .remove-variant:hover {
            background-color: #c82333;
            /* Matches btn-danger hover */
        }
    </style>
    <script>
        $(document).ready(function() {
            // Category AJAX handler
            $('body').on('change', '.main-category', function(e) {
                let id = $(this).val();
                $.ajax({
                    url: "{{ route('admin.product.get-sub-categories') }}",
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        // sub category reset completely
                        $('.sub-category').html(
                            '<option value="">Select Sub Category</option>');
                        $.each(data, function(i, item) {
                            $('.sub-category').append(
                                `<option value="${item.id}">${item.name}</option>`);
                        });
                        // Child category reset completely
                        $('.child-category').html(
                            '<option value="">Select Child Category</option>');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error status:', status);
                        console.error('Error message:', error);
                        console.error('XHR object:', xhr);
                    }
                });
            });

            // Child category AJAX handler
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
                            '<option value="">Select Child Category</option>');
                        $.each(data, function(i, item) {
                            $('.child-category').append(
                                `<option value="${item.id}">${item.name}</option>`);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error status:', status);
                        console.error('Error message:', error);
                        console.error('XHR object:', xhr);
                    }
                });
            });


            // Variant handling
            let variants = [];

            // add new variant 
            $('#variantList .variant-item').each(function() {
                let sizeId = $(this).find('input[name$="[size_id]"]').val();
                let colorId = $(this).find('input[name$="[color_id]"]').val();
                let price = $(this).find('input[name$="[price]"]').val();

                variants.push({
                    size_id: sizeId || '',
                    color_id: colorId || '',
                    price: price || ''
                });
            });

            // Add new variant
            $('#addVariant').on('click', function() {
                let sizeId = $('#variantSize').val();
                let sizeText = $('#variantSize option:selected').text();
                let colorId = $('#variantColor').val();
                let colorText = $('#variantColor option:selected').text();
                let price = $('#variantPrice').val();

                if (!sizeId && !colorId && !price) {
                    toastr.error('Please provide at least one attribute');
                    return;
                }

                // Duplicate check
                let duplicate = false;
                $('#variantList .variant-item').each(function() {
                    let existingSize = $(this).find('input[name$="[size_id]"]').val();
                    let existingColor = $(this).find('input[name$="[color_id]"]').val();
                    if ((sizeId && sizeId == existingSize) || (colorId && colorId ==
                            existingColor)) {
                        duplicate = true;
                        return false;
                    }
                });

                if (duplicate) {
                    toastr.error('This size or color is already added');
                    return;
                }

                // array add 
                variants.push({
                    size_id: sizeId || '',
                    color_id: colorId || '',
                    price: price || ''
                });

                let index = variants.length - 1;

                // hidden input make
                let inputHtml = `
                <input type="hidden" name="variants[${index}][size_id]" value="${sizeId || ''}">
                <input type="hidden" name="variants[${index}][color_id]" value="${colorId || ''}">
                <input type="hidden" name="variants[${index}][price]" value="${price || ''}">
            `;

                // display
                let displayText = [];
                if (sizeId) displayText.push(`Size: ${sizeText}`);
                if (colorId) displayText.push(`Color: ${colorText}`);
                if (price) displayText.push(`Price: ${price}`);
                let display = displayText.join(', ');

                $('#variantList').append(`
                <div class="variant-item btn btn-primary" data-index="${index}">
                    <div class="variant-content">
                        <span>${display}</span>
                        <i class="fas fa-times remove-variant"></i>
                    </div>
                    ${inputHtml}
                </div>
            `);

                // clear input
                $('#variantSize').val('');
                $('#variantColor').val('');
                $('#variantPrice').val('');
            });

            // Remove
            $(document).on('click', '.remove-variant', function() {
                let item = $(this).closest('.variant-item');
                let index = item.data('index');

                // array remove
                variants.splice(index, 1);

                // HTML remove 
                item.remove();

                // reindex
                $('#variantList .variant-item').each(function(i) {
                    $(this).data('index', i);
                    $(this).find('input[name^="variants"]').each(function() {
                        let name = $(this).attr('name');
                        let newName = name.replace(/variants\[\d+\]/, `variants[${i}]`);
                        $(this).attr('name', newName);
                    });
                });
            });

        });
    </script>
    {{-- <script>
        function toggleCustomizeSection() {
            let select = document.getElementById('is_customizable');
            let section = document.getElementById('customize_section');
            if (select.value == 1) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initial check on page load
            toggleCustomizeSection();

            // Change event
            document.getElementById('is_customizable').addEventListener('change', toggleCustomizeSection);
        });
    </script> --}}
</script>


    {{-- <script>
        $(document).ready(function() {
            let colorIndex = {{ $product->productImageGalleries->count() }};

            $('#addColorRow').click(function() {
                let html = `
        <div class="row mb-3 color-image-row" data-index="${colorIndex}">
            <div class="col-md-5">
                <select class="form-control" name="proColor[]">
                    <option value="">Select Color</option>
                    @foreach ($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="file" class="form-control" name="color_image[]">
                <img src="" width="50px" height="50px" class="mt-2 rounded-circle" style="display:none;">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger removeColorRow">Remove</button>
            </div>
        </div>`;
                $('#colorImageWrapper').append(html);
                colorIndex++;
            });

            // $(document).on('click', '.removeColorRow', function() {
            //     $(this).closest('.color-image-row').remove();
            // });
            let deletedColors = [];

            $(document).on('click', '.removeColorRow', function() {
                let row = $(this).closest('.color-image-row');
                let colorId = row.find('select[name="proColor[]"]').val();

                if (colorId) {
                    deletedColors.push(colorId);
                    $('#deleted_color_ids').val(deletedColors.join(',')); // hidden input update
                }

                row.remove();
            });
        });
    </script> --}}
    <script>
$(document).ready(function() {
    let colorIndex = {{ $product->productImageGalleries->count() }};
    let deletedColors = [];

    // Add new color row
    $('#addColorRow').click(function() {
        let html = `
        <div class="row mb-3 color-image-row" data-index="${colorIndex}">
            <div class="col-md-5">
                <select class="form-control" name="proColor[]">
                    <option value="">Select Color</option>
                    @foreach ($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="file" class="form-control color-image-input" name="color_image[]">
                <img src="" width="50px" height="50px" class="mt-2 rounded-circle color-image-preview" style="display:none;">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger removeColorRow">Remove</button>
            </div>
        </div>`;
        $('#colorImageWrapper').append(html);
        colorIndex++;
    });

    // Remove color row
    $(document).on('click', '.removeColorRow', function() {
        let row = $(this).closest('.color-image-row');
        let colorId = row.find('select[name="proColor[]"]').val();

        if (colorId) {
            deletedColors.push(colorId);
            $('#deleted_color_ids').val(deletedColors.join(','));
        }

        row.remove();
    });

    // Show preview when file selected
    $(document).on('change', '.color-image-input', function(e) {
        let input = this;
        let preview = $(this).siblings('.color-image-preview');
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                preview.attr('src', e.target.result);
                preview.show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
});
</script>

@endpush
