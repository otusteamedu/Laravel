<?php

namespace App\Ddd\Interface\Controllers;

use App\Ddd\Application\UseCases\Payments\Queries\FetchAll\Fetcher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private Fetcher $fetcher) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $payments = $this->fetcher->fetch();
        return view('admin.payments.index', compact('payments'));
    }
}
