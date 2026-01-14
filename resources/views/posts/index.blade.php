<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Posts List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="my-5 text-center">Posts List</h1>
                <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#addModal">Add Post</a>
                <div class="table-data">
                    <table class="table table-striped table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Price</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="postTable">
                            @foreach ($posts as $post)
                                <tr id="row_{{ $post->id }}">
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->description }}</td>
                                    <td>{{ $post->price }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm showBtn"
                                            data-id="{{ $post->id }}">View</button>
                                        <button class="btn btn-warning btn-sm editBtn"
                                            data-id="{{ $post->id }}">Edit</button>
                                        <button class="btn btn-danger btn-sm deleteBtn"
                                            data-id="{{ $post->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('posts.create')
                @include('posts.edit')
                @include('posts.show')
                {!! $posts->links() !!}
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function appendPostRow(post) {
                let row = `<tr id="row_${post.id}">
                        <td>${post.id}</td>
                        <td>${post.title}</td>
                        <td>${post.description}</td>
                        <td>${post.price}</td>
                        <td>
                            <button class="btn btn-info btn-sm showBtn" data-id="${post.id}">View</button>
                            <button class="btn btn-warning btn-sm editBtn" data-id="${post.id}">Edit</button>
                            <button class="btn btn-danger btn-sm deleteBtn" data-id="${post.id}">Delete</button>
                        </td>
                    </tr>`;
                $('#postTable').prepend(row);
            }

            function updatePostRow(post) {
                let row = `<td>${post.id}</td>
                           <td>${post.title}</td>
                           <td>${post.description}</td>
                           <td>${post.price}</td>
                           <td>
                              <button class="btn btn-info btn-sm showBtn" data-id="${post.id}">View</button>
                              <button class="btn btn-warning btn-sm editBtn" data-id="${post.id}">Edit</button>
                              <button class="btn btn-danger btn-sm deleteBtn" data-id="${post.id}">Delete</button>
                           </td>`;
                $(`#row_${post.id}`).html(row);
            }

            $(document).ready(function() {

                // Add post
                $('.add_post').click(function(e) {
                    e.preventDefault();

                    $('.errMsgContainer').html('');

                    let title = $('#title').val();
                    let description = $('#description').val();
                    let price = $('#price').val();

                    $.ajax({
                        url: "{{ route('posts.store') }}",
                        type: "POST",
                        data: {
                            title: title,
                            description: description,
                            price: price,
                        },
                        success: function(response) {
                            $('#addModal').modal('hide');
                            $('#title').val('');
                            $('#description').val('');
                            $('#price').val('');

                            appendPostRow(response.post);

                        },
                        error: function(error) {
                            $('.errMsgContainer').html('');
                            if (error.responseJSON && error.responseJSON.errors) {
                                let errors = error.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('.errMsgContainer').append(
                                        '<span class="text-danger">' +
                                        value + '</span><br>');
                                });
                            }
                        }
                    });
                });

                // Show post
                $(document).on('click', '.showBtn', function() {
                    let id = $(this).data('id');
                    $.get('/posts/' + id, function(res) {
                        $('#show_title').val(res.title);
                        $('#show_description').val(res.description);
                        $('#show_price').val(res.price);
                        $('#showModal').modal('show');
                    });
                });

                // edit post
                $(document).on('click', '.editBtn', function() {
                    let id = $(this).data('id');
                    $('#updateModal .errMsgContainer').html('');
                    $.get('/posts/' + id, function(res) {
                        $('#up_id').val(res.id);
                        $('#up_title').val(res.title);
                        $('#up_description').val(res.description);
                        $('#up_price').val(res.price);
                        $('#updateModal').modal('show');
                    });
                });

                // Update post
                $('.update_post').click(function(e) {
                    e.preventDefault();
                    $('#updateModal .errMsgContainer').html('');

                    let id = $('#up_id').val();
                    let title = $('#up_title').val();
                    let description = $('#up_description').val();
                    let price = $('#up_price').val();

                    $.ajax({
                        url: '/posts/' + id,
                        type: 'POST',
                        data: {
                            _method: 'PUT',
                            title: title,
                            description: description,
                            price: price,
                        },
                        success: function(response) {
                            $('#updateModal').modal('hide');

                            updatePostRow(response.post);
                        },
                        error: function(error) {
                            $('#updateModal .errMsgContainer').html('');
                            if (error.responseJSON && error.responseJSON.errors) {
                                let errors = error.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('#updateModal .errMsgContainer').append(
                                        '<span class="text-danger">' +
                                        value + '</span><br>');
                                });
                            }
                        }
                    });
                });

                // Delete post
                $(document).on('click', '.deleteBtn', function() {
                    let id = $(this).data('id');
                    if (!confirm('Are you sure?')) return;

                    $.ajax({
                        url: '/posts/' + id,
                        type: 'DELETE',
                        success: function() {
                            $('#row_' + id).remove();
                        }
                    });
                });
            });
        </script>
    </div>
</body>

</html>
