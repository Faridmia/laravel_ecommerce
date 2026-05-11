<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class AdminController extends Controller
{
    //
    public function list()
    {
      
        $data['getRecord'] = User::getAdminList();
        $data['header_title'] = 'Admin';   
        return view('admin.admin.list', $data);
    }

    public function create()
    {
        $data['header_title'] = 'Add Admin';   
        return view('admin.admin.add', $data );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'status' => 'required|in:0,1',
        ]);

        // Create new admin user
        $admin = new User();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->is_admin = 1; // Set as admin
        $admin->status = $request->status;
        $admin->save();

        return redirect()->route('admin.admin.list')->with('success', 'Admin created successfully');
    }

    public function edit($id)
    {
        $data['getRecord'] = User::findOrFail($id);
        $data['header_title'] = 'Edit Admin';   
        return view('admin.admin.edit', $data );
    }

    public function update( Request $request, $id )
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|min:6',
            'status' => 'required|in:0,1',
        ]);

        // Update admin user
        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->status = $request->status;
        $admin->save();

        return redirect()->route('admin.admin.list')->with('success', 'Admin updated successfully');
    }  
    
    public function delete($id)
    {
        $admin = User::findOrFail($id);
        $admin->is_delete= 1; // Ensure it's an admin user
        $admin->save();

        return redirect()->route('admin.admin.list')->with('success', 'Admin deleted successfully');
    }
}
