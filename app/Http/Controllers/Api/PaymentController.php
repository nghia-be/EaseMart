<?php
namespace App\Http\Controllers\Api;

use App\Models\HistoryTransaction;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;

class PaymentController extends Controller
{
    public function paymentMethod() {
        $method = PaymentMethod::all();
        return response()->json($method);
    }
    public function get(Request $request)
    {

        try {
            // Tìm đơn hàng theo mã
            $order = Order::where('order_code', $request->content)->firstOrFail();
    
            // Kiểm tra số tiền
            if ($request->money === $order->amount) {
                $payment = Payment::where('id', $order->payment_id)->first();
    
                // Kiểm tra trạng thái thanh toán trước khi cập nhật
                if ($payment->payment_status_id !== 6) {
                    $payment->update(['payment_status_id' => 6]);
                }
    
                // Lưu lịch sử giao dịch
                HistoryTransaction::create([
                    'phone' => $request->phone,
                    'type' => $request->type,
                    'gateway' => $request->gateway,
                    'payment_id' => $order->payment_id,
                    'txn_id' => $request->txn_id,
                    'content' => 'Giao dịch với mã đơn ' . $order->order_code,
                    'datetime' => now(),
                    'balance' => $request->balance,
                    'number' => $request->number,
                ]);
    
                return response()->json(['message' => 'Giao dịch thành công!'], 200);
            } else {
                return response()->json(['error' => 'Số tiền không khớp với giá trị đơn hàng'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi trong quá trình xử lý: ' . $e->getMessage()], 500);
        }
}
}