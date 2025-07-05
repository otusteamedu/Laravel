<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentsService $service
    )
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $payments = $this->service->getAll();
        return view('admin.payments.index', compact('payments'));
    }
}
