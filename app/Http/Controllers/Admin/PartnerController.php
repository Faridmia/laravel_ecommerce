<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;

class PartnerController extends Controller
{
    /**
     * List all partners.
     */
    public function list()
    {
        $data['getRecord'] = Partner::orderBy('id', 'desc')->get();
        $data['header_title'] = "Partners";
        return view('admin.partner.list', $data);
    }

    /**
     * Show add form.
     */
    public function add()
    {
        $data['header_title'] = "Add New Partner";
        return view('admin.partner.add', $data);
    }

    /**
     * Store new partner.
     */
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $partner = new Partner();
        $partner->name = trim($request->name);
        $partner->link = trim($request->link);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if (!file_exists(public_path('upload/partners'))) {
                    mkdir(public_path('upload/partners'), 0777, true);
                }
                $filename = 'partner_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/partners/'), $filename);
                $partner->image = $filename;
            }
        }

        $partner->save();
        return redirect()->route('admin.partner.list')->with('success', 'Partner added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $data['partner'] = Partner::findOrFail($id);
        $data['header_title'] = "Edit Partner";
        return view('admin.partner.edit', $data);
    }

    /**
     * Update partner.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $partner = Partner::findOrFail($id);
        $partner->name = trim($request->name);
        $partner->link = trim($request->link);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                // Delete old image if it's stored in upload directory
                if (!empty($partner->image) && file_exists(public_path('upload/partners/' . $partner->image))) {
                    @unlink(public_path('upload/partners/' . $partner->image));
                }
                
                if (!file_exists(public_path('upload/partners'))) {
                    mkdir(public_path('upload/partners'), 0777, true);
                }
                $filename = 'partner_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/partners/'), $filename);
                $partner->image = $filename;
            }
        }

        $partner->save();
        return redirect()->route('admin.partner.list')->with('success', 'Partner updated successfully.');
    }

    /**
     * Delete partner.
     */
    public function delete($id)
    {
        $partner = Partner::findOrFail($id);
        if (!empty($partner->image) && file_exists(public_path('upload/partners/' . $partner->image))) {
            @unlink(public_path('upload/partners/' . $partner->image));
        }
        $partner->delete();
        return redirect()->route('admin.partner.list')->with('success', 'Partner deleted successfully.');
    }
}
