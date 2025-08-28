<?php

namespace App\Interface\Http\API\V1;

use App\Application\Services\OrderAppService;
use App\Application\Services\CartAppService;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Mail\OrderConfirmationMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct(
        private OrderAppService $orderAppService,
        private CartAppService $cartAppService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/orders",
     *     operationId="getUserOrders",
     *     tags={"Orders"},
     *     summary="Get user orders",
     *     description="Returns paginated list of user orders",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "processing", "shipped", "delivered", "cancelled"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="total_amount", type="number", format="float"),
     *                 @OA\Property(property="shipping_address", type="string", nullable=true),
     *                 @OA\Property(property="billing_address", type="string", nullable=true),
     *                 @OA\Property(property="customer_note", type="string", nullable=true),
     *                 @OA\Property(property="items", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="product_id", type="string"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="price", type="number", format="float"),
     *                     @OA\Property(property="total", type="number", format="float")
     *                 )),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="last_page", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'status' => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('jwt')->user();
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 15;

        $criteria = [
            'user_id' => $user->id,
            'status' => $request->status,
            'sort' => 'created_at',
            'direction' => 'desc',
        ];

        $result = $this->orderAppService->getOrdersPaginated($page, $perPage, $criteria);

        return response()->json([
            'data' => array_map(fn($order) => $order->toArray(), $result['data']),
            'meta' => [
                'current_page' => $result['current_page'],
                'per_page' => $result['per_page'],
                'total' => $result['total'],
                'last_page' => $result['last_page'],
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/orders/{id}",
     *     operationId="getOrderById",
     *     tags={"Orders"},
     *     summary="Get order details",
     *     description="Returns order details by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="total_amount", type="number", format="float"),
     *                 @OA\Property(property="shipping_address", type="string", nullable=true),
     *                 @OA\Property(property="billing_address", type="string", nullable=true),
     *                 @OA\Property(property="customer_note", type="string", nullable=true),
     *                 @OA\Property(property="items", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="product_id", type="string"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="price", type="number", format="float"),
     *                     @OA\Property(property="total", type="number", format="float")
     *                 )),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $order = $this->orderAppService->getOrderById((int)$id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        // Check if user owns this order
        $user = auth('jwt')->user();
        if ($order->getUserId() !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'message' => 'Access denied'
            ], 403);
        }

        return response()->json([
            'data' => $order->toArray()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/orders",
     *     operationId="createOrder",
     *     tags={"Orders"},
     *     summary="Create new order from cart",
     *     description="Creates a new order from the current user's cart",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="X-Guest-Token",
     *         in="header",
     *         description="Guest token for anonymous users (if creating from guest cart)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="customer@example.com"),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="shipping_address", type="string", example="123 Main St, City, Country"),
     *             @OA\Property(property="billing_address", type="string", example="123 Main St, City, Country"),
     *             @OA\Property(property="customer_note", type="string", example="Please deliver after 5 PM")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="total_amount", type="number", format="float"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="name", type="string", nullable=true),
     *                 @OA\Property(property="phone", type="string", nullable=true),
     *                 @OA\Property(property="shipping_address", type="string", nullable=true),
     *                 @OA\Property(property="billing_address", type="string", nullable=true),
     *                 @OA\Property(property="customer_note", type="string", nullable=true),
     *                 @OA\Property(property="items", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="product_id", type="string"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="price", type="number", format="float"),
     *                     @OA\Property(property="total", type="number", format="float")
     *                 )),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or empty cart"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'shipping_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
            'customer_note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth('jwt')->user();
            $guestToken = $request->header('X-Guest-Token');

            // Get cart based on authentication
            if ($user) {
                $cart = $this->cartAppService->getUserCart($user->id);
                if (!$cart) {
                    return response()->json([
                        'message' => 'Cart not found'
                    ], 404);
                }
            } elseif ($guestToken) {
                $cart = $this->cartAppService->getGuestCart($guestToken);
                if (!$cart) {
                    return response()->json([
                        'message' => 'Guest cart not found'
                    ], 404);
                }
            } else {
                return response()->json([
                    'message' => 'Authentication required or guest token needed'
                ], 401);
            }


            // Create order from cart
            $order = $this->orderAppService->createOrderFromCart(
                $cart->getId(),
                $request->email,
                $request->name,
                $request->phone,
                $user ? $user->id : null,
                $request->shipping_address,
                $request->billing_address,
                $request->customer_note,

            );

            // Send confirmation email
            try {
                Mail::to($order->getEmail())->send(new OrderConfirmationMail($order));
            } catch (\Exception $e) {
                // Log email sending error but don't fail the order creation
                \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }

            return response()->json([
                'data' => $order->toArray()
            ], 201);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/orders/{id}/cancel",
     *     operationId="cancelOrder",
     *     tags={"Orders"},
     *     summary="Cancel order",
     *     description="Cancels an existing order if possible",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order cancelled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="status", type="string", example="cancelled"),
     *                 @OA\Property(property="total_amount", type="number", format="float"),
     *                 @OA\Property(property="shipping_address", type="string"),
     *                 @OA\Property(property="billing_address", type="string"),
     *                 @OA\Property(property="customer_note", type="string"),
     *                 @OA\Property(property="items", type="array", @OA\Items()),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Order cannot be cancelled"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */
    public function cancel($id): JsonResponse
    {
        try {
            $order = $this->orderAppService->getOrderById((int)$id);

            if (!$order) {
                return response()->json([
                    'message' => 'Order not found'
                ], 404);
            }

            // Check if user owns this order or is admin
            $user = auth('jwt')->user();
            if ($order->getUserId() !== $user->id && !$user->isAdmin()) {
                return response()->json([
                    'message' => 'Access denied'
                ], 403);
            }

            $cancelledOrder = $this->orderAppService->cancelOrder((int)$id);

            return response()->json([
                'data' => $cancelledOrder->toArray()
            ]);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/orders/search",
     *     operationId="searchOrders",
     *     tags={"Orders"},
     *     summary="Search orders",
     *     description="Search orders by various criteria (admin only)",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "processing", "shipped", "delivered", "cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="min_amount",
     *         in="query",
     *         description="Minimum order amount",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="max_amount",
     *         in="query",
     *         description="Maximum order amount",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="total_amount", type="number", format="float"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden (admin only)"
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $user = auth('jwt')->user();

        if (!$user->isAdmin()) {
            return response()->json([
                'message' => 'Admin access required'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
            'min_amount' => 'sometimes|numeric|min:0',
            'max_amount' => 'sometimes|numeric|min:0',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $criteria = array_filter([
            'status' => $request->status,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        $orders = $this->orderAppService->searchOrders($criteria);

        return response()->json([
            'data' => array_map(fn($order) => [
                'id' => $order->getId(),
                'user_id' => $order->getUserId(),
                'status' => $order->getStatus(),
                'total_amount' => $order->getTotalAmount(),
                'created_at' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
            ], $orders)
        ]);
    }
}
