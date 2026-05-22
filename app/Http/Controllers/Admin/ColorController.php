<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ColorModel;
use Auth;

class ColorController extends Controller
{
    public function list()
    {
        $data['getRecord'] = ColorModel::getRecord();
        $data['header_title'] = 'Color';

        return view('admin.color.list', $data);
    }

    public function create()
    {
        $data['header_title'] = 'Add Color';

        return view('admin.color.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $color = new ColorModel();
        $color->name = $request->name;
        $color->code = $request->code;
        $color->status = $request->status;
        $color->created_by = auth()->id();
        $color->save();

        return redirect()->route('admin.color.list')
            ->with('success', 'Color created successfully.');
    }

    public function edit($id)
    {
        $color = ColorModel::where('is_delete', 0)
            ->where('color_id', $id)
            ->first();

        if (!$color) {
            return redirect()->route('admin.color.list')
                ->with('error', 'Color not found.');
        }

        $data['getRecord'] = $color;
        $data['header_title'] = 'Edit Color';

        return view('admin.color.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $color = ColorModel::where('is_delete', 0)
            ->where('color_id', $id)
            ->first();

        if (!$color) {
            return redirect()->route('admin.color.list')
                ->with('error', 'Color not found.');
        }

        $color->name = $request->name;
        $color->code = $request->code;
        $color->status = $request->status;
        $color->save();

        return redirect()->route('admin.color.list')
            ->with('success', 'Color updated successfully.');
    }

    public function delete($id)
    {
        $color = ColorModel::where('is_delete', 0)
            ->where('color_id', $id)
            ->first();

        if (!$color) {
            return redirect()->route('admin.color.list')
                ->with('error', 'Color not found.');
        }

        $color->is_delete = 1;
        $color->save();

        return redirect()->route('admin.color.list')
            ->with('success', 'Color deleted successfully.');
    }
}