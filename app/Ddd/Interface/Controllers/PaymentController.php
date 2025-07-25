<?php

namespace App\Ddd\Interface\Controllers;

use App\Ddd\Application\UseCases\Payments\Commands\Store\Handler as StoreHandler;
use App\Ddd\Application\UseCases\Payments\Commands\Pay\Handler as PayHandler;
use App\Ddd\Application\UseCases\Payments\Commands\Pay\Dto as PayDto;
use App\Ddd\Application\UseCases\Payments\Commands\Cancel\Handler as CancelHandler;
use App\Ddd\Application\UseCases\Payments\Commands\Cancel\Dto as CancelDto;
use App\Ddd\Application\UseCases\Payments\Queries\FetchAll\Fetcher;
use App\Ddd\Application\UseCases\Payments\Queries\FetchByUid\Fetcher as UidFetcher;
use App\Ddd\Domain\ValueObjects\Status;
use App\Http\Controllers\Controller;
use App\Services\OrdersService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Events\PaymentConfirmed;
use App\Dto\Order\StatusDto;

class PaymentController extends Controller
{
    public function __construct(
        private Fetcher $fetcher,
        private UidFetcher $uidFetcher,
        private StoreHandler $storeHandler,
        private PayHandler $payHandler,
        private CancelHandler $cancelHandler,
        private OrdersService $ordersService
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

    public function update(): Response
    {
        $resp = file_get_contents('php://input');
        $response = json_decode($resp, true);

        $paymentUid = $response['object']['id'] ?? '';
        $paymentEvent = $response['event'] ?? '';
        $paymentStatus = explode('.', $paymentEvent)[1] ?? '';
        $paymentAmount = (int) ($response['object']['amount']['value'] ?? 0);

        if (empty($paymentUid) || empty($paymentStatus) || empty($paymentAmount)) {
            return response('', 400);
        }

        if ($paymentStatus == Status::Succeeded->value) {
            $payDto = new PayDto($paymentUid, $paymentAmount);
            $this->payHandler ->handle($payDto);
        } elseif ($paymentStatus == Status::Canceled->value) {
            $cancelDto = new CancelDto($paymentUid, $paymentAmount);
            $this->cancelHandler->handle($cancelDto);
        }

        $paymentDto = $this->uidFetcher->fetch($paymentUid);
        $orderId = $paymentDto->order_id;
        $statusDto = new StatusDto($orderId, $paymentStatus);
        
        $this->ordersService->updateStatus($statusDto);

        PaymentConfirmed::dispatch($resp);
        return response('', 200);
    }
}
