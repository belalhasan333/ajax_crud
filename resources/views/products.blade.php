<!DOCTYPE html>
<html>

<head>
    <title>Laravel 12 Ajax CRUD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <h2 class="card-header"><i class="fa-regular fa-credit-card"></i> Laravel 12 Ajax CRUD</h2>
            <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-success btn-sm" id="createNewProduct"><i class="fa fa-plus"></i> Create New
                        Product</button>
                </div>
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th width="60px">No</th>
                            <th>Name</th>
                            <th>Details</th>
                            <th width="280px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" name="product_id" id="product_id">
                        @csrf
                        <div class="alert alert-danger print-error-msg d-none">
                            <ul></ul>
                        </div>
                        <div class="mb-3">
                            <label>Name:</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter Name">
                        </div>
                        <div class="mb-3">
                            <label>Details:</label>
                            <textarea class="form-control" id="detail" name="detail" placeholder="Enter Details"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" id="saveBtn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Show -->
    <div class="modal fade" id="showModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa-regular fa-eye"></i> Show Product</h4>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span class="show-name"></span></p>
                    <p><strong>Details:</strong> <span class="show-detail"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'detail',
                        name: 'detail'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#createNewProduct').click(function() {
                $('#saveBtn').val("create-product");
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                $('#modelHeading').html("<i class='fa fa-plus'></i> Create New Product");
                $('#ajaxModel').modal('show');
            });

            $('body').on('click', '.showProduct', function() {
                var id = $(this).data('id');
                $.get("{{ route('products.index') }}/" + id, function(data) {
                    $('#showModel').modal('show');
                    $('.show-name').text(data.name);
                    $('.show-detail').text(data.detail);
                });
            });

            $('body').on('click', '.editProduct', function() {
                var id = $(this).data('id');
                $.get("{{ route('products.index') }}/" + id + "/edit", function(data) {
                    $('#modelHeading').html(
                        "<i class='fa-regular fa-pen-to-square'></i> Edit Product");
                    $('#saveBtn').val("edit-product");
                    $('#ajaxModel').modal('show');
                    $('#product_id').val(data.id);
                    $('#name').val(data.name);
                    $('#detail').val(data.detail);
                });
            });

            $('#productForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('products.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#ajaxModel').modal('hide');
                        $('#productForm').trigger("reset");
                        table.draw();
                    },
                    error: function(err) {
                        let ul = $('.print-error-msg ul');
                        ul.html('');
                        $('.print-error-msg').removeClass('d-none');
                        $.each(err.responseJSON.errors, function(key, value) {
                            ul.append('<li>' + value + '</li>');
                        });
                    }
                });
            });

            $('body').on('click', '.deleteProduct', function() {
                var id = $(this).data("id");
                if (confirm("Are you sure want to delete?")) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('products') }}/" + id,
                        success: function(data) {
                            table.draw();
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                }
            });

        });
    </script>

</body>

</html>
