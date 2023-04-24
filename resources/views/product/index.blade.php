@extends('layouts.layout')
@section('title', 'Product')
@section('css')
    <style>
        .product-image img{
            width: 80px;
            height: 80px;
        }
    </style>
@endsection
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3"></div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                @if(Session('message'))
                <div class="alert alert-success">
                    {{ Session('message') }}
                </div>
                @endif
            </div>
        </div>
    </div>


    <div class="container mt-5">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-md-4">
                <h4 class="fw-bold"><span class="text-muted fw-light">Product /</span> Form</h4>
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-end">
                    <div class="search-main d-flex align-items-center me-4">
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input type="text" id="livesearch" class="form-control" placeholder="Search...">
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
                        Add Product
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="add-product" id="addProduct" action="{{ route('add-product') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <select class="form-select" id="category" name="category[]" multiple="">
                                    <option value="">-- select category --</option>
                                    @foreach($category as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                    @endforeach
                                  </select>
                            </div>
                            <input type="hidden" name="productid" id="productid"> 

                            <div class="form-group mb-3">
                                <label class="form-label">Product Name</label>
                                <div class="input-group">
                                    <input type="text" id="title" name="title" class="form-control">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group">
                                    <input type="file" id="image" name="featured_image" class="form-control">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Gallery</label>
                                <div class="input-group">
                                    <input type="file" id="gallery" name="gallery[]" class="form-control" multiple>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="ckeditor form-control" id="description" name="description"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label d-block m-0">Status</label>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="radio" name="status" id="active" value="1" checked>
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inactive" value="2">
                                    <label class="form-check-label" for="inactive">Inactive</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">Product Data</h5>
                    <div class="table-responsive text-nowrap" id="product_table">
                        
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@section('js')
<script>
    // Product Validation
    $('#addProduct').validate({
    rules: {
        title: {
            required: true
        },
        "category[]": "required"
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    }
    });

    // Edit Product
    $(document).on('click','.editproduct', function () {
        var id = $(this).data('id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: 'product/' + id + '/edit-product',
            dataType: 'json',
            success: function (data) {
                if(data.status == 'true') {
                    var productData = data.data
                    console.log(productData);
                    $('#productid').val(productData.id);
                    $('#title').val(productData.title);
                    $.each(productData.category.split(","), function(key, val){
                        $("#category option[value='" + val + "']").prop("selected", true);
                    });
                    // $('#description').val(productData.description);
                    if(productData.status == 1){
                        $('#active').prop('checked', true);
                    }else{
                        $('#inactive').prop('checked', true);
                    }
                }
            },
        });
    });

    // Search Product
    var qstring = 'searchproduct=';
    getProductData(qstring);
    $(document).on('keyup','#livesearch',function(){
        search = $(this).val();
        qstring = 'search='+ search;
        getProductData(qstring);
        var query = $(this).val();

    });

    function getProductData(qstring)
    {
        $.ajax({
            url: 'product?'+qstring,
            type: 'GET',
            dataType:'json',
            success:function(data)
            {
                $('#product_table').html(data.data);
            },
            error: function(e) {
            }
        });
    }

    // Delete Product
    $(document).on('click', '#deleteProduct', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        swal({
            title: 'Are you sure want to delete this Product?',
            icon: 'warning',
            buttons: ["Cancel", "Yes!"],
        })
        .then((Done) => {
            if(Done){
                productDelete(id);
            }
        });
    });

    function productDelete(id) {
        let url = "{{ route('delete-product', ':id') }}";
        url = url.replace(':id', id);
        $.ajax({
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: url,
            success: function(data) {
                if(data.status == 200){
                    getProductData(qstring);
                    swal({
                        title: "Product deleted succsessfully",
                        icon: "success",
                        timer: 1500
                    });
                }
            }
        });
    };

    // CK Editor

    ClassicEditor
    .create(document.querySelector('#description'));
    
</script>

@endsection