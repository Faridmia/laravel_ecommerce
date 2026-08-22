<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    /**
     * List registered customers with filters.
     */
    public function list(Request $request)
    {
        $query = User::where('is_admin', 0)->where('is_delete', 0);

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . trim($request->name) . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . trim($request->email) . '%');
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $total = $query->count();
        $getRecord = $query->orderBy('id', 'desc')->paginate(20);

        $data['getRecord'] = $getRecord;
        $data['total'] = $total;
        $data['header_title'] = 'Customer List';

        return view('admin.customer.list', $data);
    }

    /**
     * Soft delete a customer.
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->is_delete = 1;
        $user->save();

        return redirect()->back()->with('success', 'Customer deleted successfully.');
    }
}
