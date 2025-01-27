<?php
namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Stock;
use App\Models\Status;
use App\Models\Payment;
use App\Models\Products;
use App\Models\Shipping;

use App\Models\OrderDetail;
use App\Models\ProductUnits;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function test()
    {
        $orders = Order::all();
        dd($orders);

    return response()->json($orders); // Trả về các đơn hàng
    }
        
    public function createOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
            'items.*.unit_code' => 'required|string',
            'payment_method' => 'required|string', // 'COD' or 'online'
            'total_amount' => 'required', // Nhận total_amount từ request
        ]);
    
        DB::beginTransaction();
        $customer = auth()->user();
    
        try {
            // 1. Create Order
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_code' => 'ORD' . time(),
                'order_status_id' => Status::where('status_code', 'pending')
                     ->where('type', 'order')
                     ->first()
                     ->id,
                'total_amount' => $request->total_amount, // Sử dụng total_amount từ request
                'voucher_id' => $request->voucher_id ?? null,
                'shipping_address' => $customer->address,
            ]);
    
            // 2. Add Items to OrderDetail and Update Stock
            foreach ($request->items as $item) {
                $productUnit = ProductUnits::where('product_id', $item['product_id'])->firstOrFail();
                $quantity = $item['quantity'];
    
                if ($productUnit->level < 3) {
                    $quantity *= $productUnit->exchangerate;
                }
    
                $stock = Stock::where('product_id', $item['product_id'])->first();
                if (!$stock || $stock->quantity < $quantity) {
                    return response()->json(['message' => 'Không đủ số lượng trong kho'], 400);
                }
    
                // Decrement stock
                $stock->decrement('quantity', $quantity);
    
                // Create OrderDetail
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $productUnit->price,
                    'unit' => $item['unit_code'],
                    'total_price' => $productUnit->price * $item['quantity'],
                ]);
            }
    
            // 3. Handle Payment
            if ($request->payment_method == 'COD') {
                $paymentStatus = Status::where('status_code', 'pending')
                    ->where('type', 'payment')
                    ->first()
                    ->id;
    
                // Tạo payment với status là "pending"
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount' => $request->total_amount,
                    'payment_method' => 'COD',
                    'payment_date' => null,
                    'payment_status_id' => $paymentStatus,
                ]);
            } else {
                // Xử lý thanh toán trực tuyến (online)
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount' => $request->total_amount,
                    'payment_method' => $request->payment_method,
                    'payment_date' => now(),
                    'payment_status_id' => Status::where('status_code', 'pending')
                         ->where('type', 'payment')
                         ->first()
                         ->id,
                ]);
            }
    
            $order->update(['payment_id' => $payment->id]);
    
            DB::commit();
    
            return response()->json(['message' => 'Tạo đơn hàng thành công', 'order_code' => $order->order_code]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xác thực.',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Order creation failed', 'error' => $e->getMessage()], 500);
        }
    }
    







}
