<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    {{-- <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet"> --}}
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>


    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <title>Document</title>
</head>

<body>
    <div class="container-fluid">
        <a href="logout" class="float-right" style="float: right;">Logout</a>
        <h2>Article</h2>
        <br>
        <div class="form-group">
            <button class="btn btn-success btn-md mb-5" data-toggle="modal" data-target = '#demoModal' id="createButton">Create</button>
        </div>
        <div class="container border border-1 p-5">
            <table class="table  table-hover align-middle data-table" id = 'drawtable'>
                <thead>
                    <tr>
                        {{-- <th>No</th> --}}
                        <th>Email</th>
                        <th>Title</th> 
                        <th>Article</th>
                        <th style="width:100px">Active</th>
                    </tr>
                </thead>
                <tbody id="article-array">
                </tbody>
            </table>

        </div>
    </div>


    <div class="modal fade" id="demoModal">
        <div class="modal-dialog" role="document">
            <form action="{{ route('article.insert') }}" method="POST" id="article-form">
                @csrf

                <input type="text" hidden id="format" name="format">
                <input type="text" hidden id="id" name="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Create Article</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="validation-errors" class="alert alert-danger" style="display:none;"></div>

                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" name="title" class="form-control" id="title">
                        </div>
                        <div class="form-group">
                            <label for="article">Article</label>
                            <textarea name="article" id="article" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" id="closeModal" data- 
                        dismiss="modal">Close</button> --}}
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="closeModal" >Close</button>
                        <button type="submit" id="article-send" class="btn btn-success btn-submit">Save
                            changes</button>
                    </div>
                </div>
        </div>
        </form>
    </div>
</body>

</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
console.log($.fn.jquery);
console.log($.fn.tooltip.Constructor.VERSION);
    @if (Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}"
        switch (type) {
            case 'info':

                toastr.options.timeOut = 10000;
                toastr.info("{{ Session::get('message') }}");
                break;
            case 'success':
                toastr.options.timeOut = 10000;
                toastr.success("{{ Session::get('message') }}");

                break;
            case 'warning':

                toastr.options.timeOut = 10000;
                toastr.warning("{{ Session::get('message') }}");

                break;
            case 'error':

                toastr.options.timeOut = 10000;
                toastr.error("{{ Session::get('message') }}");

                break;
        }
    @endif

    let initial_data = [];
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function getArticle() {
        console.log(321231231);
        initial_data = [];
        $("#drawtable").DataTable({
            processing: true,
            serverSiding: true,
            responsive: true,
            ajax: '{{route('articles.get')}}',
            order: [[1, 'asc']],
            columns: [
                    // { data: 'id', name: 'id' },
                    { data: 'email', name: 'email' },
                    { data: 'title', name: 'title' },
                    { data: 'article', name: 'article' },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
        })
        // $.ajax({
        //     type: 'GET', // Use curly braces for object definition
        //     url: "/articles.get",
        //     success: function(response) {
        //         initial_data = response;
        //         displayArticle(response);
        //     },
        //     error: function(xhr) {
        //         console.log('Error', xhr.responseText);
        //     }
        // });
    }
    getArticle();

    function displayArticle(article) {
        $("#article-array").html('');
        for (let i = 0; i < article.length; i++) {
            $("#article-array").append('<tr><td>' + (i + 1) + '</td><td>' + article[i].email + '</td><td>' + article[i]
                .title + '</td><td>' + article[i].article +
                '</td><td class = "d-flex   justify-content-center gap-3" ><button class = "btn btn-primary btn-sm" data-toggle="modal" data-target = "#demoModal" onClick = "editArticle(' +
                i + ')">Edit</button><button class = "btn btn-primary btn-sm" onClick = "deleteArticle(' + article[
                    i].id +
                ')">Delete</button></td></tr>')

        }
    }

    function editArticle(id) {
        $.ajax({
            type:'get',
            url:'article.get',
            data: {id},
            success: function (response) {
                $("#article").val(response.article);
                $("#title").val(response.title);
                $("#format").val('update');
                $("#id").val(response.id);
            },
            error: function(xhr) {
                alert(xhr);
            }
        })
    }
    
    $("#createButton").click(function() {
        $("#format").val('create');
        $("#title").val('');
        $("#article").val('');

    })

    function deleteArticle(index) {
        Swal.fire({
            title: "Do you want to delete?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Delete",
            denyButtonText: `Don't delete`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type: 'delete',
                    url: '/article.delete',
                    data: {
                        index
                    },
                    success: function(response) {
                        
                    $('#drawtable').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        alert(xhr);
                    }
                })
                Swal.fire("Deleted!", "", "success");
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });
    }
    $('#article-form').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        console.log(form.serialize());
        let formData = {
            title:$("#title").val(),
            article:$("#article").val(),
        }
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                // $('#demoModal').modal('hide');
                $('#closeModal').click();
                toastr.options.timeOut = 10000;
                toastr.success(response.message);
                $('#drawtable').DataTable().ajax.reload();
                $('#validation-errors').html('').hide();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '<ul>';
                $.each(errors, function(key, value) {
                    errorHtml += '<li>' + value[0] + '</li>';  // Show first error
                });
                errorHtml += '</ul>';
                $('#validation-errors').html(errorHtml).show();
            }
        });
    });

</script>

</html>
