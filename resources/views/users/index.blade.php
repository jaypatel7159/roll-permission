@extends('layouts.app')

@section('content')
    <div class="row">

        <div class="col-lg-6 margin-tb">
            <div class="pull-left">
                <h2>Users Management</h2>
            </div>
            <div class="pull-right">
                @can('user-create')
                    <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
                @endcan
            </div>

        </div>
        <div class="col-lg-6  margin-tb">
            <div class="pull-left">
                {{-- @can('user-create') --}}
                {{-- <input class="form-control" type="file" id="formFile">
                <br>
                <a class="btn btn-success" href="{{ route('users.import') }}"> Import User Data</a>
                <a class="btn btn-primary" href="{{ route('users.export') }}"> Export User Data</a> --}}
                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control">
                    <br>
                    <button class="btn btn-success float-right">Import User Data</button>
                </form><br>

                <a class="btn btn-primary float-right" href="{{ route('userscrete') }}">Download PDF</a>
                <br>
            </div>
        </div>
    </div>



    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <style>
        a.btn.btn-default.buttons-html5 {
            border: 1px solid;
        }
    </style>

    <table class="table table-bordered user_datatable" id="user_datatable">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Hobbie</th>
                <th>Image</th>
                <th class="not-export-col" width="280px">Action </th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    {{-- <button class="btn btn-success" onclick="tablesToExcel([user_datatable], ['User','ulist.xlsx','Excel'])">Export to
        excel</button> --}}
    {{-- <table id="myTable" class="table table-bordered data-filter-control">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Hobbie</th>
                <th>Image</th>
                <th width="280px">Action <a class="btn btn-warning float-end" href="{{ route('users.export') }}">Export
                        User
                        Data</a></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $user)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if (!empty($user->getRoleNames()))
                            @foreach ($user->getRoleNames() as $v)
                                <label class="badge badge-success text-capitalize">{{ $v }}</label>
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $user->hobbie }}</td>
                    <td><img src="/storage/images/{{ $user->image }} " width="50px" height="50px"></td>
                    <td>
                        <a class="btn btn-info" href="{{ route('users.show', $user->id) }}">Show</a>
                        @can('user-edit')
                            <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}">Edit</a>
                        @endcan
                        @can('user-delete')
                            {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'style' => 'display:inline']) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        @endcan
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table> --}}



    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });



        $(function() {
            function getBase64Image(url, callback) {
                var img = new Image();
                img.crossOrigin = "anonymous";
                var x = img.onload = function() {
                    var canvas = document.createElement("canvas");
                    canvas.width = this.width;
                    canvas.height = this.height;
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(this, 0, 0);
                    var dataURL = canvas.toDataURL("image/png");
                    callback(dataURL);

                };
                img.src = url;
            }
            var table = $('.user_datatable').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtlp',
                // buttons: ['csv', 'pdf', 'excel'],
                buttons: [{
                        text: 'csv',
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'excel',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'pdf',
                        extend: 'pdfHtml5',
                        customize: function(doc) {
                            //find paths of all images
                            var arr2 = $('.img-fluid').map(function() {
                                return this.src;
                            }).get();
                            for (var i = 0, c = 1; i < arr2.length; i++, c++) {
                                getBase64Image(arr2[i], function(result) {
                                    doc.content[1].table.body[c][0] = {
                                        image: result,
                                        width: 100
                                    }

                                });

                            }

                        },

                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },

                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                ajax: "{{ route('users.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'hobbie',
                        name: 'hobbie'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });



            $(document).on("click", ".delete_btn", function() {
                var delId = $(this).data("data-id");

                $.ajax({
                    url: "{{ route('users.destroy', 'delId') }}",
                    type: 'DELETE',
                    data: {
                        id: $(this).attr("data-id"),
                    },
                    success: function(data) {
                        table.ajax.reload();
                        // sweetAlert("Data Delete successfully");
                    }
                });

            });

        });
    </script>
@endsection
