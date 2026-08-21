<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\District;
use App\Models\Area;
use App\Models\ShippingZone;
use App\Models\ShippingZoneLocation;
use App\Models\ShippingMethod;
use App\Models\ShippingRate;
use Cart;

class ShippingController extends Controller
{
    public function getDivisions($country_id)
    {
        $divisions = Division::where('country_id', $country_id)->orderBy('name', 'asc')->get();
        return response()->json($divisions);
    }

    public function getDistricts($division_id)
    {
        $districts = District::where('division_id', $division_id)->orderBy('name', 'asc')->get();
        return response()->json($districts);
    }

    public function getAreas($district_id)
    {
        $areas = Area::where('district_id', $district_id)->orderBy('name', 'asc')->get();
        return response()->json($areas);
    }

    public function calculateRates(Request $request)
    {
        $country_id = $request->country_id;
        $division_id = $request->division_id;
        $district_id = $request->district_id;
        $area_id = $request->area_id;

        if (empty($country_id)) {
            return response()->json(['status' => false, 'message' => 'Country is required.']);
        }

        // Calculate cart total weight and subtotal
        $subtotal = Cart::getSubTotal();
        $totalWeight = 0;

        foreach (Cart::getContent() as $item) {
            $product = \App\Models\ProductModel::find($item->id);
            $weight = ($product && !empty($product->weight)) ? $product->weight : 0;
            $totalWeight += $weight * $item->quantity;
        }

        // WooCommerce-like Shipping Zone Matching Algorithm
        $matchedZone = null;

        // 1. Match specific Area
        if (!empty($area_id)) {
            $location = ShippingZoneLocation::where('country_id', $country_id)
                ->where('division_id', $division_id)
                ->where('district_id', $district_id)
                ->where('area_id', $area_id)
                ->first();
            if ($location) {
                $matchedZone = ShippingZone::where('is_active', 1)->find($location->shipping_zone_id);
            }
        }

        // 2. Match specific District
        if (!$matchedZone && !empty($district_id)) {
            $location = ShippingZoneLocation::where('country_id', $country_id)
                ->where('division_id', $division_id)
                ->where('district_id', $district_id)
                ->whereNull('area_id')
                ->first();
            if ($location) {
                $matchedZone = ShippingZone::where('is_active', 1)->find($location->shipping_zone_id);
            }
        }

        // 3. Match specific Division
        if (!$matchedZone && !empty($division_id)) {
            $location = ShippingZoneLocation::where('country_id', $country_id)
                ->where('division_id', $division_id)
                ->whereNull('district_id')
                ->whereNull('area_id')
                ->first();
            if ($location) {
                $matchedZone = ShippingZone::where('is_active', 1)->find($location->shipping_zone_id);
            }
        }

        // 4. Match Country
        if (!$matchedZone) {
            $location = ShippingZoneLocation::where('country_id', $country_id)
                ->whereNull('division_id')
                ->whereNull('district_id')
                ->whereNull('area_id')
                ->first();
            if ($location) {
                $matchedZone = ShippingZone::where('is_active', 1)->find($location->shipping_zone_id);
            }
        }

        // If no zone matches, fallback to the default zone (e.g. named Everywhere or ID 1, or first active zone with no locations)
        if (!$matchedZone) {
            $matchedZone = ShippingZone::where('is_active', 1)
                ->whereDoesntHave('locations')
                ->first();
        }

        if (!$matchedZone) {
            return response()->json([
                'status' => false,
                'message' => 'No shipping methods available for your location.',
                'rates' => []
            ]);
        }

        // Get shipping methods for this zone
        $methods = ShippingMethod::where('shipping_zone_id', $matchedZone->id)
            ->where('is_active', 1)
            ->with('rates')
            ->get();

        $availableRates = [];

        foreach ($methods as $method) {
            // Find applicable rate within the method
            foreach ($method->rates as $rate) {
                // Check if min order amount is satisfied
                if (!empty($rate->min_order_amount) && $subtotal < $rate->min_order_amount) {
                    continue; // skip this rate, minimum order amount not met
                }

                // Check weight bounds
                if ($rate->min_weight !== null && $totalWeight < $rate->min_weight) {
                    continue;
                }
                if ($rate->max_weight !== null && $totalWeight > $rate->max_weight) {
                    continue;
                }

                $availableRates[] = [
                    'rate_id' => $rate->id,
                    'method_id' => $method->id,
                    'method_name' => $method->name,
                    'type' => $method->type,
                    'charge' => $rate->charge,
                    'estimated_days' => $rate->estimated_days ?: '',
                    'free_shipping' => (bool)$rate->free_shipping
                ];
                // In WooCommerce, we typically take the first matched rate per method
                break;
            }
        }

        return response()->json([
            'status' => true,
            'zone_name' => $matchedZone->name,
            'rates' => $availableRates
        ]);
    }

    public function selectShippingRate(Request $request)
    {
        $rateId = $request->rate_id;
        $charge = floatval($request->charge);
        $methodName = $request->method_name;

        session()->put('shipping_rate', [
            'rate_id' => $rateId,
            'charge' => $charge,
            'method_name' => $methodName
        ]);

        $subtotal = Cart::getSubTotal();
        $discount = 0;
        $coupon = session()->get('coupon');
        if ($coupon) {
            if ($coupon->discount_type == 'percentage') {
                $discount = ($subtotal * $coupon->discount_value) / 100;
                if (!empty($coupon->maximum_discount) && $discount > $coupon->maximum_discount) {
                    $discount = $coupon->maximum_discount;
                }
            } else {
                $discount = $coupon->discount_value;
            }
        }

        $total = $subtotal - $discount + $charge;

        return response()->json([
            'status' => true,
            'charge' => number_format($charge, 2),
            'total' => number_format($total, 2)
        ]);
    }
}
