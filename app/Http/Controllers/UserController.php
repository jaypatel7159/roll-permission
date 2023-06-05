<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Http\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Storage;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
// use PDF;
use Barryvdh\DomPDF\Facade\Pdf;


class UserController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::get();

            $user = auth()->user();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) use ($user) {
                    $btn = '';

                    $btn =  '<a class="btn btn-info" href="' . route('users.show', $row->id) . '" data-id="' . $row->id . '"">Show</a> ';
                    if ($user->can('user-edit')) {
                        $btn = $btn . '<a class="btn btn-primary" href="' . route('users.edit', $row->id) . '"  data-id="' . $row->id . '" ">Edit</a> ';
                    }
                    if ($user->can('user-delete')) {
                        $btn = $btn . '<button class="btn btn-danger delete_btn" data-id="' . $row->id . '">Delete</button>';
                    }

                    return $btn;
                })
                ->addColumn('image', function ($row) {
                    $image = '<img src="/storage/images/' . $row->image . ' " class="img-fluid" width="50px" height="50px">';
                    return $image;
                })
                ->addColumn('role', function ($row) {
                    $role = '<label class="badge badge-success text-capitalize">' .  $row->getRoleNames()  . '</label>';
                    return $role;
                })
                ->rawColumns(['action', 'image', 'role'])
                ->make(true);
        }


        return view('users.index');

        // $data = User::orderBy('id', 'DESC')->paginate(5);
        // return view('users.index', compact('data'))
        //     ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            // 'roles' => 'required',
            'hobbies.*' => 'required',
            'image' => 'required',

        ]);
        // dd(implode(",", $request->hobbies));
        // $input = $request->all();
        // // // //dd($input);
        // $input['password'] = Hash::make($input['password']);

        // foreach ($request->hobbies as $key => $value) {
        //     User::create($value);
        // }


        $user = User::create([
            $img = $request->file('image')->getClientOriginalName(),
            Storage::disk('public')->putFileAs('images', new File($request->file('image')), $img),


            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request['password']),
            "hobbie" => implode(", ", $request->hobbie),
            "image" => $img,

        ]);
        //dd($user);
        // $user = User::create($input);

        $user->assignRole($request->input('roles'));

        return response()->json(['data' => $user, 'msg' => 'user create']);
        // return redirect()->route('users.index')
        //     ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find(id);
        $hobb = explode(",", $user->hobbie);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'hobb', 'roles', 'userRole'));
        // return response()->json(["msg" => "edit", "user" => $user, "hobb" => $hobb, "roles" => $roles, "userRole" => $userRole]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
            'hobbie.*' => 'required',
            'image' => 'required'
        ]);
        // dd($request);
        if (!empty($request['password'])) {
            $request['password'] = Hash::make($request['password']);
        } else {
            $request = Arr::except($request, array('password'));
        }
        if ($request->file('image')) {
            $img = $request->file('image')->getClientOriginalName();
            Storage::disk('public')->putFileAs('images', new File($request->file('image')), $img);
            $user = User::find($id);
            $user->update([
                "image" => $img,
            ]);
        }

        // $input = $request->all();
        $user = User::find($id);
        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            // "password" => Hash::make($request['password']),
            "hobbie" => implode(",", $request->hobbie),
        ]);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        return response()->json(["msg" => "Data Delete Successfully"]);
        // return redirect()->route('users.index')
        //     ->with('success', 'User deleted successfully');
    }

    public function viewPDF()
    {
        $data = User::all();
        return view('users.pdfview', compact('data'));
    }
    public function createPDF()
    {
        // retreive all records from db
        $data = User::all();
        // share data to view
        $user = ['data' => $data];

        $pdf = PDF::loadView('users.pdfview', $user);
        // download PDF file with download method
        return $pdf->download('pdf-file.pdf');
    }


    public function export()
    {

        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import()
    {
        Excel::import(new UsersImport, request()->file('file'));

        return back();
    }
}
