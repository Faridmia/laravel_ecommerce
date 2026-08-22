<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Payment Gateways';
        $data['gateways'] = PaymentGateway::getAllGateways();
        return view('admin.payment_gateways', $data);
    }

    public function update(Request $request)
    {
        $gatewaysData = $request->input('gateways', []);

        foreach ($gatewaysData as $gatewayKey => $data) {
            $gateway = PaymentGateway::where('gateway_key', $gatewayKey)->first();
            if ($gateway) {
                $gateway->status = isset($data['status']) && $data['status'] === 'yes' ? 'yes' : 'no';
                $gateway->mode = $data['mode'] ?? 'sandbox';
                $gateway->public_key = isset($data['public_key']) ? trim($data['public_key']) : null;
                $gateway->secret_key = isset($data['secret_key']) ? trim($data['secret_key']) : null;
                if (isset($data['name'])) {
                    $gateway->name = trim($data['name']);
                }
                if (isset($data['description'])) {
                    $gateway->description = trim($data['description']);
                }
                $gateway->save();
            }
        }

        return redirect()->back()->with('success', 'Payment gateways updated successfully.');
    }
}
