<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payments.index');
    }

    public function create()
    {
        $members = Member::where('company_id', auth()->user()->company_id)->get();
        return view('payments.create', compact('members'));
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'msisdn' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'network' => 'required|in:MTN,AIRTEL',
            'description' => 'nullable|string|max:255',
        ]);

        // Validate phone number
        $phone = $this->validatePhone($request->msisdn);
        if (!$phone) {
            return redirect()->back()->with('error', 'Invalid phone number. Use: 2567XXXXXXXX')->withInput();
        }

        // ✅ Get secret key from .env only (NO HARDCODED FALLBACK)
        $secretKey = env('FLUTTERWAVE_SECRET_KEY');

        // ✅ Check if key exists
        if (!$secretKey) {
            Log::error('Flutterwave Secret Key is missing in .env file');
            return redirect()->back()->with('error', 'Payment configuration error. Please contact support.')->withInput();
        }

        // ✅ Log to debug (only first few characters)
        Log::info('Using Secret Key: ' . substr($secretKey, 0, 20) . '...');

        $payload = [
            'tx_ref' => 'TBA-' . time() . '-' . rand(1000, 9999),
            'amount' => $request->amount,
            'currency' => 'UGX',
            'redirect_url' => route('payments.callback'),
            'payment_options' => 'mobilemoney',
            'customer' => [
                'name' => $request->customer_name,
                'email' => $request->customer_email,
                'phonenumber' => $phone,
            ],
            'customizations' => [
                'title' => 'TBA Finance Cloud',
                'description' => $request->description ?? 'SACCO Payment',
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.flutterwave.com/v3/payments', $payload);

            $result = $response->json();

            if ($result['status'] === 'success') {
                session(['tx_ref' => $payload['tx_ref']]);
                return redirect()->away($result['data']['link']);
            }

            Log::error('Flutterwave Payment Error', $result);
            return redirect()->back()->with('error', $result['message'] ?? 'Payment initialization failed')->withInput();

        } catch (\Exception $e) {
            Log::error('Flutterwave Exception', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Payment service error: ' . $e->getMessage())->withInput();
        }
    }

    public function callback(Request $request)
    {
        $transactionId = $request->query('transaction_id');

        if (!$transactionId) {
            return redirect()->route('payments.index')->with('error', 'No transaction ID provided');
        }

        // ✅ Get secret key from .env only (NO HARDCODED FALLBACK)
        $secretKey = env('FLUTTERWAVE_SECRET_KEY');

        if (!$secretKey) {
            Log::error('Flutterwave Secret Key is missing in .env file');
            return view('payments.failed', ['message' => 'Payment configuration error. Please contact support.']);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

            $result = $response->json();

            if ($result['status'] === 'success' && isset($result['data']['status']) && $result['data']['status'] === 'successful') {
                return view('payments.success', ['transaction' => $result['data']]);
            }

            return view('payments.failed', ['message' => $result['message'] ?? 'Payment verification failed']);

        } catch (\Exception $e) {
            Log::error('Flutterwave Verify Error', ['message' => $e->getMessage()]);
            return view('payments.failed', ['message' => 'Verification error: ' . $e->getMessage()]);
        }
    }

    protected function validatePhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) === 12 && substr($phone, 0, 3) === '256') {
            return $phone;
        }

        if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            return '256' . substr($phone, 1);
        }

        return null;
    }
}