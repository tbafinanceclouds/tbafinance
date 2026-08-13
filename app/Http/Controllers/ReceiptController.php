<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function show($id)
    {
        $receipt = Receipt::with(['company', 'member', 'creator'])
            ->where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        return view('receipts.show', compact('receipt'));
    }

    public function pdf($id)
    {
        $receipt = Receipt::with(['company', 'member', 'creator'])
            ->where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $pdf = Pdf::loadView('receipts.pdf', compact('receipt'));
        return $pdf->download('receipt-' . $receipt->receipt_number . '.pdf');
    }
}