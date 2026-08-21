<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingZone;
use App\Models\ShippingZoneLocation;
use App\Models\ShippingMethod;
use App\Models\ShippingRate;
use App\Models\Country;
use App\Models\Division;
use App\Models\District;
use App\Models\Area;

class ShippingZoneController extends Controller
{
    public function list()
    {
        $data['getRecord'] = ShippingZone::getRecord();
        $data['header_title'] = 'Shipping Zones';

        return view('admin.shipping.zones.list', $data);
    }

    public function create()
    {
        $data['header_title'] = 'Add New Shipping Zone';
        return view('admin.shipping.zones.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'is_active' => 'required|in:0,1',
        ]);

        $zone = new ShippingZone();
        $zone->name = trim($request->name);
        $zone->is_active = $request->is_active;
        $zone->save();

        return redirect()->route('admin.shipping.zones.edit', $zone->id)
            ->with('success', 'Shipping Zone created successfully. Now configure locations and methods.');
    }

    public function edit($id)
    {
        $zone = ShippingZone::with(['locations.country', 'locations.division', 'locations.district', 'locations.area', 'methods.rates'])
            ->findOrFail($id);

        $data['getRecord'] = $zone;
        $data['countries'] = Country::orderBy('name', 'asc')->get();
        $data['header_title'] = 'Edit Shipping Zone';

        return view('admin.shipping.zones.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'is_active' => 'required|in:0,1',
        ]);

        $zone = ShippingZone::findOrFail($id);
        $zone->name = trim($request->name);
        $zone->is_active = $request->is_active;
        $zone->save();

        return redirect()->route('admin.shipping.zones.edit', $zone->id)
            ->with('success', 'Shipping Zone updated successfully.');
    }

    public function storeLocation(Request $request, $zone_id)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        // Check if this location already exists in this zone
        $exists = ShippingZoneLocation::where('shipping_zone_id', $zone_id)
            ->where('country_id', $request->country_id)
            ->where('division_id', $request->division_id ?: null)
            ->where('district_id', $request->district_id ?: null)
            ->where('area_id', $request->area_id ?: null)
            ->exists();

        if (!$exists) {
            ShippingZoneLocation::create([
                'shipping_zone_id' => $zone_id,
                'country_id' => $request->country_id,
                'division_id' => $request->division_id ?: null,
                'district_id' => $request->district_id ?: null,
                'area_id' => $request->area_id ?: null,
            ]);
        }

        return redirect()->route('admin.shipping.zones.edit', $zone_id)
            ->with('success', 'Region added to zone successfully.');
    }

    public function delete($id)
    {
        $zone = ShippingZone::findOrFail($id);
        $zone->delete();

        return redirect()->route('admin.shipping.zones.list')
            ->with('success', 'Shipping Zone deleted successfully.');
    }

    public function deleteLocation($location_id)
    {
        $location = ShippingZoneLocation::findOrFail($location_id);
        $zone_id = $location->shipping_zone_id;
        $location->delete();

        return redirect()->route('admin.shipping.zones.edit', $zone_id)
            ->with('success', 'Location removed from zone successfully.');
    }

    // Shipping Methods Action
    public function storeMethod(Request $request, $zone_id)
    {
        $request->validate([
            'method_name' => 'required|max:255',
            'method_type' => 'required|in:flat_rate,free_shipping,local_pickup',
        ]);

        $method = new ShippingMethod();
        $method->shipping_zone_id = $zone_id;
        $method->name = trim($request->method_name);
        $method->type = $request->method_type;
        $method->is_active = true;
        $method->save();

        // Create a default rate for the method
        ShippingRate::create([
            'shipping_method_id' => $method->id,
            'charge' => $method->type == 'free_shipping' ? 0 : 50, // default charge
            'min_order_amount' => 0,
            'free_shipping' => $method->type == 'free_shipping',
            'estimated_days' => '3-5 days'
        ]);

        return redirect()->route('admin.shipping.zones.edit', $zone_id)
            ->with('success', 'Shipping Method added successfully.');
    }

    public function updateMethod(Request $request, $method_id)
    {
        $method = ShippingMethod::findOrFail($method_id);
        $method->is_active = $request->has('is_active') ? $request->is_active : $method->is_active;
        if ($request->has('name')) {
            $method->name = trim($request->name);
        }
        $method->save();

        return response()->json(['status' => true, 'message' => 'Shipping method updated successfully.']);
    }

    public function deleteMethod($method_id)
    {
        $method = ShippingMethod::findOrFail($method_id);
        $zone_id = $method->shipping_zone_id;
        $method->delete();

        return redirect()->route('admin.shipping.zones.edit', $zone_id)
            ->with('success', 'Shipping Method deleted successfully.');
    }

    // Shipping Rates Action
    public function storeRate(Request $request, $method_id)
    {
        $request->validate([
            'charge' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|max:255',
        ]);

        $method = ShippingMethod::findOrFail($method_id);

        $rate = new ShippingRate();
        $rate->shipping_method_id = $method_id;
        $rate->charge = $request->charge;
        $rate->min_order_amount = $request->min_order_amount ?: 0;
        $rate->min_weight = $request->min_weight;
        $rate->max_weight = $request->max_weight;
        $rate->free_shipping = $method->type == 'free_shipping';
        $rate->estimated_days = $request->estimated_days;
        $rate->save();

        return redirect()->route('admin.shipping.zones.edit', $method->shipping_zone_id)
            ->with('success', 'Shipping Rate configuration saved.');
    }

    public function deleteRate($rate_id)
    {
        $rate = ShippingRate::findOrFail($rate_id);
        $method = ShippingMethod::findOrFail($rate->shipping_method_id);
        $rate->delete();

        return redirect()->route('admin.shipping.zones.edit', $method->shipping_zone_id)
            ->with('success', 'Shipping Rate deleted successfully.');
    }

    public function updateMethodForm(Request $request, $method_id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $method = ShippingMethod::findOrFail($method_id);
        $method->name = trim($request->name);
        $method->save();

        return redirect()->route('admin.shipping.zones.edit', $method->shipping_zone_id)
            ->with('success', 'Shipping Method renamed successfully.');
    }

    public function updateRate(Request $request, $rate_id)
    {
        $request->validate([
            'charge' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|max:255',
        ]);

        $rate = ShippingRate::findOrFail($rate_id);
        $rate->charge = $request->charge;
        $rate->min_order_amount = $request->min_order_amount ?: 0;
        $rate->min_weight = $request->min_weight;
        $rate->max_weight = $request->max_weight;
        $rate->estimated_days = $request->estimated_days;
        $rate->save();

        return redirect()->route('admin.shipping.zones.edit', $rate->method->shipping_zone_id)
            ->with('success', 'Shipping Rate updated successfully.');
    }
}
