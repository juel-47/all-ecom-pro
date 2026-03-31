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
            <h4>Create Product</h4>
            <div class="card-header-action">
              <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Back</a>
            </div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label>Product Name <code>*</code></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="inputState">Category <code>*</code> </label>
                  <select id="inputState" class="form-control main-category" name="category" required>
                    <option value="">select category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="inputState">Sub Category</label>
                  <select id="inputState" class="form-control sub-category" name="sub_category">
                    <option value="">select</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="inputState">Child Category</label>
                  <select id="inputState" class="form-control child-category" name="child_category">
                    <option value="">select</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="inputState">Brand</label>
                  <select id="inputState" class="form-control" name="brand">
                    <option value="">Brands</option>
                    @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label>SKU</label>
                  <input type="text" class="form-control" name="sku" value="{{ old('sku') }}" required>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label>Purchase Price </label>
                  <input type="text" class="form-control" name="purchase_price" value="{{ old('purchase_price') }}">
                </div>
                <div class="form-group col-md-4">
                  <label>Price <code>*</code></label>
                  <input type="text" class="form-control" name="price" value="{{ old('price') }}" required>
                </div>
                <div class="form-group col-md-4">
                  <label>Offer Price</label>
                  <input type="text" class="form-control" name="offer_price" value="{{ old('offer_price') }}">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label>Offer Start Date</label>
                  <input type="datetime-local" name="offer_start_date" class="form-control">
                </div>
                <div class="form-group col-md-6">
                  <label>Offer End Date</label>
                  <input type="datetime-local" name="offer_end_date" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label>Stock Quantity</label>
                  <input type="number" min="0" class="form-control" name="qty" value="{{ old('qty') }}">
                </div>
                <div class="form-group col-md-6">
                  <label>Video Link </label>
                  <input type="text" class="form-control" name="video_link" value="{{ old('video_link') }}">
                </div>
              </div>
              <div class="form-group">
                <label>Short Description <code>*</code></label>
                <textarea name="short_description" class="form-control"
                  required>{{ old('short_description') }}</textarea>
              </div>
              <div class="form-group">
                <label>Long Description <code>*</code></label>
                <textarea name="long_description" class="form-control summernote"
                  >{{ old('long_description') }}</textarea>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="inputState">Product Type</label>
                  <select id="inputState" class="form-control" name="product_type">
                    <option value="">select</option>
                    <option value="new_arrival">New Arrival</option>
                    <option value="featured_product">Featured</option>
                    <option value="top_product">Top Product</option>
                    <option value="best_product">Best Product</option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label>Meta Title</label>
                  <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}">
                </div>
              </div>
              <div class="form-group">
                <label>Meta Description</label>
                <textarea name="meta_description" class="form-control"></textarea>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="inputState">Status</label>
                  <select id="inputState" class="form-control" name="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label>Image</label>
                  <input type="file" class="form-control" name="image" required>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label>Image alt text</label>
                  <input type="text" class="form-control" name="image_alt_text">
                </div>
                {{-- <div class="form-group col-md-4">
                                        <label for="color">Color</label>
                                        <select class="form-control select2" name="proColor[]" multiple>
                                            <option value="">Select Color</option>
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->id }}">
                {{ $color->color_name }}</option>
                @endforeach
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="size">Size</label>
                <select class="form-control select2" name="proSize[]" multiple>
                  <option value="">Select Size</option>
                  @foreach ($sizes as $size)
                  <option value="{{ $size->id }}">{{ $size->size_name }}</option>
                  @endforeach
                </select>
              </div> --}}
          </div>
          <!-- Size, Color, Price Combinations -->
          <div class="form-group">
            <label>Add Size, Color, and Price Combinations</label>
            <div class="row mb-3">
              <div class="col-md-5">
                <select class="form-control" id="variantSize" name="proSize[]">
                  <option value="">Select Size (Optional)</option>
                  @foreach ($sizes as $size)
                  <option value="{{ $size->id }}">{{ $size->size_name }}</option>
                  @endforeach
                </select>
              </div>
              {{-- <div class="col-md-4">
                                            <select class="form-control" id="variantColor" name="proColor[]">
                                                <option value="">Select Color (Optional)</option>
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}">{{ $color->color_name }}</option>
              @endforeach
              </select>
            </div> --}}
            <div class="col-md-5">
              <input type="number" class="form-control" id="variantPrice" placeholder="vartiant Price (Optional)"
                min="0" step="0.01" name="variant_price">
            </div>
            <div class="col-md-1">
              <button type="button" class="btn btn-primary" id="addVariant">Add</button>
            </div>
          </div>
          <div class="mt-3" id="variantList"></div>
        </div>
        {{-- <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Add Color with Image </label>
                                        <select class="form-control" id="variantColor" name="proColor">
                                            <option value="">Select Color (Optional)</option>
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->id }}">{{ $color->color_name }}</option>
        @endforeach
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label>Image</label>
        <input type="file" class="form-control" name="color_image" required>
      </div>
    </div> --}}
    <div id="colorImageWrapper">

      <div class="row colorImageRow">
        <div class="col-md-5 mb-3">
          <label>Select Color</label>
          <select class="form-control" name="proColor[]">
            <option value="">Select Color</option>
            @foreach ($colors as $color)
            <option value="{{ $color->id }}">{{ $color->color_name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-5 mb-3">
          <label>Image</label>
          <input type="file" class="form-control colorImageInput" name="color_image[]">

          <!-- Image Preview -->
          <img class="color-preview-img mt-2 d-none" />
        </div>

        <div class="col-md-2 mt-4">
          <button type="button" class="btn btn-danger removeRow">X</button>
        </div>
      </div>

    </div>

    <button type="button" id="addMore" class="btn btn-primary mt-2">Add More Color +
      Image</button>

    {{-- <hr> --}}

    <hr>
    <button type="submit" class="btn btn-primary mt-3">Create</button>
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
<style>
.color-preview-img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #ddd;
  padding: 2px;
  background: white;
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
        $('.sub-category').html(
          '<option value="">Select Sub Category</option>');
        $.each(data, function(i, item) {
          $('.sub-category').append(
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

  $('#addVariant').on('click', function() {
    let sizeId = $('#variantSize').val();
    let sizeText = $('#variantSize option:selected').text();
    let colorId = $('#variantColor').val();
    let colorText = $('#variantColor option:selected').text();
    let price = $('#variantPrice').val();

    // Check if at least one field is provided
    if (!sizeId && !colorId && !price) {
      toastr.error('please provide at least one attribute');
      return;
    }

    // Add to variants array
    variants.push({
      size_id: sizeId || '',
      size_text: sizeId ? sizeText : 'N/A',
      color_id: colorId || '',
      color_text: colorId ? colorText : 'N/A',
      price: price || ''
    });

    // Add hidden inputs for form submission
    let input = `<input type="hidden" name="variants[${variants.length-1}][size_id]" value="${sizeId || ''}">
                            <input type="hidden" name="variants[${variants.length-1}][color_id]" value="${colorId || ''}">
                            <input type="hidden" name="variants[${variants.length-1}][price]" value="${price || ''}">`;

    // Display variant
    let displayText = [];
    if (sizeId) displayText.push(`Size: ${sizeText}`);
    if (colorId) displayText.push(`Color: ${colorText}`);
    if (price) displayText.push(`Price: ${price}`);
    let display = displayText.length > 0 ? displayText.join(', ') : 'No attributes selected';

    $('#variantList').append(`
                    <div class="variant-item btn btn-primary" data-index="${variants.length-1}">
                        <div class="variant-content">
                            <span>${display}</span>
                            <i class="fas fa-times remove-variant"></i>
                        </div>
                        ${input}
                    </div>
                `);

    // Clear inputs
    $('#variantSize').val('');
    $('#variantColor').val('');
    $('#variantPrice').val('');
  });

  // Remove variant
  $(document).on('click', '.remove-variant', function() {
    let item = $(this).closest('.variant-item');
    let index = item.data('index');
    variants.splice(index, 1);
    item.remove();

    // Reindex remaining variants
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
</script>


<script>
// ADD MORE BUTTON
document.getElementById('addMore').addEventListener('click', function() {

  let wrapper = document.getElementById('colorImageWrapper');

  let firstRow = document.querySelector('.colorImageRow');

  let newRow = firstRow.cloneNode(true);

  // reset fields
  newRow.querySelector('select').value = '';
  newRow.querySelector('input[type="file"]').value = '';

  // reset preview image
  let img = newRow.querySelector('.color-preview-img');
  img.src = '';
  img.classList.add('d-none');

  wrapper.appendChild(newRow);
});


// REMOVE BUTTON
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('removeRow')) {

    // At least 1 row always keep
    if (document.querySelectorAll('.colorImageRow').length > 1) {
      e.target.closest('.colorImageRow').remove();
    }
  }
});


// IMAGE PREVIEW (Circle)
document.addEventListener('change', function(e) {

  if (e.target.classList.contains('colorImageInput')) {

    let imgTag = e.target.closest('.col-md-5').querySelector('.color-preview-img');

    let file = e.target.files[0];

    if (file) {
      imgTag.src = URL.createObjectURL(file);
      imgTag.classList.remove('d-none');
    }
  }

});
</script>
@endpush