<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessageModel;

class ContactMessageController extends Controller
{
    /**
     * List contact message submissions.
     */
    public function list(Request $request)
    {
        $data['getRecord'] = ContactMessageModel::getRecord($request->all());
        $data['header_title'] = 'Contact Us';
        return view('admin.contact.list', $data);
    }

    /**
     * Delete a contact message submission.
     */
    public function delete($id)
    {
        $message = ContactMessageModel::findOrFail($id);
        $message->delete();

        return redirect()->back()->with('success', 'Contact message deleted successfully.');
    }
}
