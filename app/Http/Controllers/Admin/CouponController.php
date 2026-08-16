<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CouponModel;
use Auth;

class CouponController extends Controller
{
    public function list()
    {
        $data['getRecord'] = CouponModel::getRecord();
        $data['header_title'] = 'Coupons';

        return view('admin.coupon.list', $data);
    }

    public function create()
    {
        $data['header_title'] = 'Add New Coupon';

        return view('admin.coupon.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'discount_type' => 'required',
            'discount_value' => 'required|numeric',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'usage_limit' => 'nullable|integer',
            'usage_limit_per_user' => 'nullable|integer',
            'minimum_order_amount' => 'nullable|numeric',
            'maximum_discount' => 'nullable|numeric',
        ]);

        $coupon = new CouponModel();
        $coupon->code = trim($request->code);
        $coupon->description = $request->description;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->minimum_order_amount = !empty($request->minimum_order_amount) ? $request->minimum_order_amount : 0;
        $coupon->maximum_discount = $request->maximum_discount;
        $coupon->starts_at = $request->starts_at;
        $coupon->expires_at = $request->expires_at;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->usage_limit_per_user = $request->usage_limit_per_user;
        $coupon->first_order_only = !empty($request->first_order_only) ? 1 : 0;
        $coupon->free_shipping = !empty($request->free_shipping) ? 1 : 0;
        $coupon->status = $request->status;
        $coupon->created_by = auth()->id();
        $coupon->save();

        return redirect()->route('admin.coupon.list')
            ->with('success', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $coupon = CouponModel::where('is_delete', 0)
            ->where('id', $id)
            ->first();

        if (!$coupon) {
            return redirect()->route('admin.coupon.list')
                ->with('error', 'Coupon not found.');
        }

        $data['getRecord'] = $coupon;
        $data['header_title'] = 'Edit Coupon';

        return view('admin.coupon.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,' . $id,
            'discount_type' => 'required',
            'discount_value' => 'required|numeric',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'usage_limit' => 'nullable|integer',
            'usage_limit_per_user' => 'nullable|integer',
            'minimum_order_amount' => 'nullable|numeric',
            'maximum_discount' => 'nullable|numeric',
        ]);

        $coupon = CouponModel::where('is_delete', 0)
            ->where('id', $id)
            ->first();

        if (!$coupon) {
            return redirect()->route('admin.coupon.list')
                ->with('error', 'Coupon not found.');
        }

        $coupon->code = trim($request->code);
        $coupon->description = $request->description;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->minimum_order_amount = !empty($request->minimum_order_amount) ? $request->minimum_order_amount : 0;
        $coupon->maximum_discount = $request->maximum_discount;
        $coupon->starts_at = $request->starts_at;
        $coupon->expires_at = $request->expires_at;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->usage_limit_per_user = $request->usage_limit_per_user;
        $coupon->first_order_only = !empty($request->first_order_only) ? 1 : 0;
        $coupon->free_shipping = !empty($request->free_shipping) ? 1 : 0;
        $coupon->status = $request->status;
        $coupon->save();

        return redirect()->route('admin.coupon.list')
            ->with('success', 'Coupon updated successfully.');
    }

    public function delete($id)
    {
        $coupon = CouponModel::where('is_delete', 0)
            ->where('id', $id)
            ->first();

        if (!$coupon) {
            return redirect()->route('admin.coupon.list')
                ->with('error', 'Coupon not found.');
        }

        $coupon->is_delete = 1;
        $coupon->save();

        return redirect()->route('admin.coupon.list')
            ->with('success', 'Coupon deleted successfully.');
    }
}
