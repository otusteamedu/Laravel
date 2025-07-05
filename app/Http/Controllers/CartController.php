<?php

namespace App\Http\Controllers;

use App\Dto\Order\StatusDto;
use App\Dto\Payment\UpdateDto;
use App\Events\PaymentConfirmed;
use App\Exceptions\StockIsEmptyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\DeliveryRequest;
use App\Services\CartService;
use App\Services\OrdersService;
use App\Services\PaymentsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Dto\Admin\Order\StoreDto;

class CartController extends Controller
{
    public function __construct(
        private CartService $service,
        private OrdersService $ordersService,
        private PaymentsService $paymentsService
    ) {}

    public function index(): View
    {
        $cart = $this->service->getCart();
        $address = $this->service->getAddress();
        $lat = $this->service->getLat();
        $lon = $this->service->getLon();
        $total = $this->service->calculateTotal($cart);
        
        return view('cabinet.cart', compact('cart', 'total', 'address', 'lat', 'lon'));
    }

    public function add(int $productId): RedirectResponse
    {
        try {
            $this->service->add($productId);
        } catch (StockIsEmptyException $e) {
            return redirect()->route('cart.index')->with('error', 'Товара недостаточно для добавления в корзину');
        }
        
        return redirect()->route('cart.index')->with('success', 'Товар добавлен в корзину');
    }

    public function remove(int $productId)
    {
        $this->service->remove($productId);
        
        return redirect()->route('cart.index')->with('success', 'Товар удален из корзины');
    }

    public function clear()
    {
        $this->service->clearCart();
        
        return redirect()->route('cart.index')->with('success', 'Корзина очищена');
    }

    public function delivery(DeliveryRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        $this->service->setDelivery($data);
        
        return response()->json([
            'success' => true,
        ]);
    }

    public function pickup(): JsonResponse
    {
        $this->service->unsetDelivery();
        
        return response()->json([
            'success' => true,
        ]);
    }

    public function order(): View
    {
        $cart = $this->service->getCart();
        $total = $this->service->calculateTotal($cart);
        $address = $this->service->getAddress();
        $deliveryPrice = $this->service->getDeliveryPrice();
        
        if (!empty($address) && empty($deliveryPrice)) {
            $address .= ' - К сожалению, на ваш адрес доставка не осуществляется. Возможен только самовывоз';
        }
        $address = !empty($address) ? $address : 'Самовывоз';
        $totalPrice = $total + $deliveryPrice;
        
        return view('cabinet.order', compact('cart', 'total', 'address', 'deliveryPrice', 'totalPrice'));
    }

    public function confirm(): RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = auth()->id();
        $dto = new StoreDto($userId);
        $cart = $this->service->getCart();
        $productIds = [];
        $counts = [];
        foreach ($cart as $productId => $item) {
            $productIds[] = $productId;
            $counts[] = $item['quantity'];
        }

        $this->ordersService->add($dto, $productIds, $counts);
        $this->service->clearCart();

        return redirect()->route('profile.history', $userId);
    }

    public function processNotification()
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

        $dto = new UpdateDto($paymentUid, $paymentStatus, $paymentAmount);
        $this->paymentsService->update($dto);

        $payment = $this->paymentsService->getByUid($paymentUid);
        $orderId = $payment->getOrderId();
        $statusDto = new StatusDto($orderId, $paymentStatus);
        $this->ordersService->updateStatus($statusDto);

        PaymentConfirmed::dispatch($resp);
        return response('', 200);
    }
}
