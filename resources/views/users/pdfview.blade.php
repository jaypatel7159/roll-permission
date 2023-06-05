<html>

<head>
    <title>user</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <h1 align="center">User List</h1>
    <table id="myTable" class="table table-bordered data-filter-control">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Hobbie</th>
                <th>Image</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $user)
                <tr>
                    <td>{{ $user->id }}</td>
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
                    <td><img src="/storage/images/{{ $user->image }} " width="50px" height="50px">
                    </td>

                </tr>
            @endforeach

        </tbody>
    </table>

</body>

</html>
