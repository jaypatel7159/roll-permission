<?php

namespace App\Exports;

use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::select("id", "name", "email")->get();
        return User::all();
        // $other    = $request->input('other');

        // $query = DB::table('User')->select()
        //     ->where('name', 'LIKE', '%' . $other . '%')
        //     ->orWhere('email', 'LIKE', '%' . $other . '%')
        //     ->orWhere('role', 'LIKE', '%' . $other . '%')
        //     ->orWhere('hobbie', 'LIKE', '%' . $other . '%')
        //     ->get();

        // dd($query);
    }


    public function headings(): array
    {
        return ["ID", "Name", "Email"];
    }
}
