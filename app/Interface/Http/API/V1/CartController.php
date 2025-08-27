<?php

namespace App\Interface\Http\API\V1;

use App\Application\Services\CartAppService;
use App\Interface\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function __construct(
        private CartAppService $cartAppService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/cart",
     *     operationId="getCart",
     *     tags={"Cart"},
     *     summary="Get cart contents",
     *     description="Returns the current cart contents for user or guest",
     *     @OA\Parameter(
     *         name="X-Guest-Token",
     *         in="header",
     *         description="Guest token for anonymous users",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="user_id", type="integer", nullable=true),
     *                 @OA\Property(property="guest_token", type="string", nullable=true),
     *                 @OA\Property(property="items", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="string"),
     *                     @OA\Property(property="product", ref="#/components/schemas/Product"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="total", type="number", format="float"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )),
     *                 @OA\Property(property="total", type="number", format="float"),
     *                 @OA\Property(property="total_quantity", type="integer"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $cart = $this->getOrCreateCart($request);

            return response()->json([
                'data' => $cart->toArray()
            ]);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/cart/items",
     *     operationId="addItemToCart",
     *     tags={"Cart"},
     *     summary="Add item to cart",
     *     description="Adds a product to the cart with specified quantity",
     *     @OA\Parameter(
     *         name="X-Guest-Token",
     *         in="header",
     *         description="Guest token for anonymous users",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id", "quantity"},
     *             @OA\Property(property="product_id", type="string", example="1"),
     *             @OA\Property(property="quantity", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="user_id", type="integer", nullable=true),
     *                 @OA\Property(property="guest_token", type="string", nullable=true),
     *                 @OA\Property(property="items", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="string"),
     *                     @OA\Property(property="product", ref="#/components/schemas/Product"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="total", type="number", format="float"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )),
     *                 @OA\Property(property="total", type="number", format="float"),
     *                 @OA\Property(property="total_quantity", type="integer"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found or cart not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function addItem(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        try {
            $cart = $this->getOrCreateCart($request);

            $updatedCart = $this->cartAppService->addItem(
                $cart->getId(),
                $request->product_id,
                $request->quantity
            );

            return response()->json([
                'data' => $updatedCart->toArray()
            ]);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/cart/items/{item}",
     *     operationId="updateCartItem",
     *     tags={"Cart"},
     *     summary="Update cart item quantity",
     *     description="Updates the quantity of a specific item in the cart",
     *     @OA\Parameter(
     *         name="item",
     *         in="path",
     *         description="Product ID (not cart item ID)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="X-Guest-Token",
     *         in="header",
     *         description="Guest token for anonymous users",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantity"},
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="user_id", type="integer", nullable=true),
     *                 @OA\Property(property="guest_token", type="string", nullable=true),
     *                 @OA\Property(property="items", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="string"),
     *                     @OA\Property(property="product", ref="#/components/schemas/Product"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="total", type="number", format="float"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )),
     *                 @OA\Property(property="total", type="number", format="float"),
     *                 @OA\Property(property="total_quantity", type="integer"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cart or item not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function updateItem(Request $request, string $productId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cart = $this->getOrCreateCart($request);
            $updatedCart = $this->cartAppService->updateItemQuantity(
                $cart->getId(),
                $productId,
                $request->quantity
            );

            return response()->json([
                'data' => $updatedCart->toArray()
            ]);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/cart/items/{item}",
     *     operationId="removeCartItem",
     *     tags={"Cart"},
     *     summary="Remove item from cart",
     *     description="Removes a specific item from the cart",
     *     @OA\Parameter(
     *         name="item",
     *         in="path",
     *         description="Product ID (not cart item ID)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="X-Guest-Token",
     *         in="header",
     *         description="Guest token for anonymous users",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item removed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="user_id", type="integer", nullable=true),
     *                 @OA\Property(property="guest_token", type="string", nullable=true),
     *                 @OA\Property(property="items", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="string"),
     *                     @OA\Property(property="product", ref="#/components/schemas/Product"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="total", type="number", format="float"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )),
     *                 @OA\Property(property="total", type="number", format="float"),
     *                 @OA\Property(property="total_quantity", type="integer"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cart or item not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function removeItem(Request $request, string $productId): JsonResponse
    {
        try {
            $cart = $this->getOrCreateCart($request);
            $updatedCart = $this->cartAppService->removeItem(
                $cart->getId(),
                $productId
            );

            return response()->json([
                'data' => $updatedCart->toArray()
            ]);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/cart/clear",
     *     operationId="clearCart",
     *     tags={"Cart"},
     *     summary="Clear cart",
     *     description="Removes all items from the cart",
     *     @OA\Parameter(
     *         name="X-Guest-Token",
     *         in="header",
     *         description="Guest token for anonymous users",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cart cleared successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="user_id", type="integer", nullable=true),
     *                 @OA\Property(property="guest_token", type="string", nullable=true),
     *                 @OA\Property(property="items", type="array", @OA\Items()),
     *                 @OA\Property(property="total", type="number", format="float", example=0),
     *                 @OA\Property(property="total_quantity", type="integer", example=0),
     *                 @OA\Property(property="expires_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function clear(Request $request): JsonResponse
    {
        try {
            $cart = $this->getOrCreateCart($request);
            $updatedCart = $this->cartAppService->clearCart($cart->getId());

            return response()->json([
                'data' => $updatedCart->toArray()
            ]);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/cart/transfer-to-user",
     *     operationId="transferCartToUser",
     *     tags={"Cart"},
     *     summary="Transfer guest cart to user",
     *     description="Transfers guest cart contents to authenticated user's cart",
     *     @OA\Parameter(
     *         name="X-Guest-Token",
     *         in="header",
     *         description="Guest token to transfer from",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cart transferred successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="guest_token", type="string", nullable=true),
     *                 @OA\Property(property="items", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="string"),
     *                     @OA\Property(property="product", ref="#/components/schemas/Product"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="total", type="number", format="float"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )),
     *                 @OA\Property(property="total", type="number", format="float"),
     *                 @OA\Property(property="total_quantity", type="integer"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Guest cart not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function transferToUser(Request $request): JsonResponse
    {
        $guestToken = $request->header('X-Guest-Token');

        if (!$guestToken) {
            return response()->json([
                'message' => 'Guest token is required'
            ], 422);
        }

        try {
            $user = auth('jwt')->user();
            $updatedCart = $this->cartAppService->transferGuestCartToUser($guestToken, $user->id);

            return response()->json([
                'data' => $updatedCart->toArray()
            ]);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get or create cart based on request context
     */
    private function getOrCreateCart(Request $request)
    {
        $user = auth('jwt')->user();
        $guestToken = $request->header('X-Guest-Token');


        if ($user) {
            // Authenticated user - use user cart
            $cart = $this->cartAppService->getUserCart($user->id);
            if (!$cart) {
                $cart = $this->cartAppService->createUserCart($user->id);
            }
            return $cart;
        }

        if ($guestToken) {
            // Guest user with token - use guest cart
            $cart = $this->cartAppService->getGuestCart($guestToken);
            if (!$cart) {
                $cart = $this->cartAppService->createGuestCart($guestToken);
            }
            return $cart;
        }

        // New guest - create cart with new token
        $newGuestToken = Str::random(32);
        return $this->cartAppService->createGuestCart($newGuestToken);
    }

    /**
     * Generate guest token response header
     */
    private function withGuestTokenHeader($response, $guestToken)
    {
        return $response->header('X-Guest-Token', $guestToken);
    }
}
