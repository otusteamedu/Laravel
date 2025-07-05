<?php

namespace App\Ddd\Interface\Controllers;

use App\Ddd\Application\UseCases\Payments\Commands\Store\Handler as StoreHandler;
use App\Ddd\Application\UseCases\Payments\Queries\FetchAll\Fetcher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        private Fetcher $fetcher,
        private StoreHandler $storeHandler
    ) {}

    public function index(Request $request): View
    {
        $payments = $this->fetcher->fetch();
        return view('admin.payments.index', compact('payments'));
    }

    public function add(int $orderId): RedirectResponse
    {
        try {
            $confirmationUrl = $this->storeHandler->handle($orderId);
            return redirect($confirmationUrl);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back();
        }
    }
}
