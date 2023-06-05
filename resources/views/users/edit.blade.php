@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit New User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </div>


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {!! Form::model($user, [
        'method' => 'PATCH',
        'route' => ['users.update', $user->id],
        'enctype' => 'multipart/form-data',
    ]) !!}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Password:</strong>
                {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Confirm Password:</strong>
                {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Role:</strong>
                @foreach ($userRole as $role)
                    {{ $role }}
                @endforeach
                @role('Admin')
                    {!! Form::select('roles[]', $roles, $userRole, ['class' => 'form-control']) !!}
                @endrole
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Image:</strong>
                <img src="/storage/images/{{ $user->image }} " width="100px">
                {!! Form::file('image', ['class' => 'form-control dropzone', 'id' => 'image-upload']) !!}
            </div>
        </div>
        <table class="table table-bordered mt-3" id="dynamicAddRemove">
            <tr>
                <th>Hobbies</th>
                <th>Action</th>
            </tr>


            @foreach ($hobb as $key => $hobbs)
                <tr>

                    <td>
                        <input type="text" name="hobbie[]" value="{{ $hobbs }}" placeholder="Enter hobbie"
                            class="form-control" />
                        {{-- < input type="text" value="{{ old('field.' . $i) }}" name="field[]" class="form-control" /> --}}
                    </td>
                    @if ($key == '0')
                        <td><button type="button" name="add" id="dynamic-ar" class="btn btn-outline-primary">Add
                                Hobbie</button></td>
                    @endif
                    @if ($key > '0')
                        <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td>
                        {{-- @else
                        <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td> --}}
                    @endif
                </tr>
            @endforeach
        </table>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    {!! Form::close() !!}




    <script type="text/javascript">
        var i = 0;
        $("#dynamic-ar").click(function() {
            ++i;
            $("#dynamicAddRemove").append(
                '<tr><td><input type="text" name="hobbie[]" placeholder="Enter hobbie" class="form-control" /></td><td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td></tr>'
            );
        });

        // $(document).ready(function() {
        //     if (i >= 1) {
        //         $(document).on('click', '.remove-input-field', function() {
        //             $(this).parents('tr').remove();
        //         });
        //     }
        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();


        });
        // });
    </script>
@endsection
