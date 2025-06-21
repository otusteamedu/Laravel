<?php

namespace App\Http\Controllers\Admin;

use App\Dto\Admin\Order\StoreDto;
use App\Dto\Admin\Order\UpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\StoreRequest;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Services\ProductsService;
use App\Services\UsersService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\OrdersService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Exceptions\OrderNotFoundException;
use Illuminate\Http\RedirectResponse;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function __construct(
        private OrdersService $service,
        private UsersService $usersService,
        private ProductsService $productsService
    )
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $sort = $request->get('sort', 'id');
        $allowedSorts = config('custom.ordersSorts');
        $sort = in_array($sort, $allowedSorts) ? $sort : 'id';

        $direction = $request->get('direction', 'asc');
        $allowedDirections= config('custom.ordersDirections');
        $direction = in_array($direction, $allowedDirections) ? $direction: 'asc';

        $page = (int) ($request->get('page') ?? 1);

        $orders = $this->service->getList($sort, $direction, $page);
        return view('admin.orders.index', compact('orders', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = $this->usersService->getAll();
        $products = $this->productsService->getAll();
        return view('admin.orders.create', compact('users', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $dto = new StoreDto($data['user_id']);
        $product_ids = $data['product_id'];
        $counts = $data['count'];

        $this->service->add($dto, $product_ids, $counts);

        return redirect()->route('admin.orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $orderId): View
    {
        try {
            $order = $this->service->getById($orderId);
        } catch (OrderNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $products = $order->getProducts();
        $total = 0;
        foreach ($products as $product) {
            $total += $product->pivot->paid_price * $product->pivot->count;
        }

        $data = [
            'orderId' => $order->getId(),
            'clientName' => $order->getUser()->name,
            'clientEmail' => $order->getUser()->email,
            'products' => $products,
            'total' => $total,
            'createdAt' => $order->getCreatedAt()->format('d.m.Y H:i'),
            'updatedAt' => $order->getUpdatedAt()->format('d.m.Y H:i'),
        ];

        return view('admin.orders.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $orderId): View
    {
        try {
            $order = $this->service->getById($orderId);
        } catch (OrderNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $users = $this->usersService->getAll();
        $products = $this->productsService->getAll();

        $data = [
            'orderId' => $order->getId(),
            'userId' => $order->getUserId(),
            'orderProducts' => $order->getProducts(),
            'users' => $users,
            'products' => $products,
        ];

        return view('admin.orders.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $orderId): RedirectResponse
    {
        $data = $request->validated();
        $dto = new UpdateDto($orderId, $data['user_id']);
        $product_ids = $data['product_id'];
        $counts = $data['count'];

        try {
            $this->service->update($dto, $product_ids, $counts);
        } catch (OrderNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return redirect()->route('admin.orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $orderId): RedirectResponse
    {
        try {
            $this->service->delete($orderId);
        } catch (OrderNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Невозможно выполнить удаление']);
        }

        return redirect()->route('admin.orders.index');
    }

    public function export() 
    {
        return Excel::download(new OrdersExport($this->service), 'orders.xlsx');
    }
}
