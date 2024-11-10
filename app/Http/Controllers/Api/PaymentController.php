<?php
namespace App\Http\Controllers\Api;

use App\Models\HistoryTransaction;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function paymentMethod() {
        $method = PaymentMethod::all();
        return response()->json($method);
    }
    public function get(Request $request)
    {

        try {
            $content = $request->content;
            $content = str_replace('CUSTOMER ', '', $content); // Loại bỏ "CUSTOMER "
            $parts = explode(' ', $content);
            $order_code = $parts[0]; // Lấy mã đơn hàng từ chuỗi

        // Tìm đơn hàng theo mã
        $order = Order::where('order_code', $order_code)->firstOrFail();
    

            // Kiểm tra số tiền
            if ($request->money == $order->total_amount) {
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
                    'content' => 'Giao dịch với mã đơn ' . $order_code,
                    'datetime' => $request->datetime,
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

public function checkPaymentStatus(Request $request)
{
    // Validate input
    $request->validate([
        'payment_id' => 'required|integer|exists:payments,id',
        'timeout' => 'required|integer', // Time limit in seconds
    ]);

    // Lấy payment_id và timeout từ request
    $paymentId = $request->payment_id;
    $timeout = $request->timeout;

    // Xác định thời gian kết thúc dựa trên thời gian timeout
    $endTime = Carbon::now()->addSeconds($timeout);

    // Kiểm tra trạng thái thanh toán
    while (Carbon::now()->lt($endTime)) {
        // Lấy payment từ database
        $payment = Payment::find($paymentId);

        // Kiểm tra nếu payment không tồn tại hoặc trạng thái thanh toán thành công
        if ($payment && $payment->payment_status_id == 6) {
            return response()->json([
                'message' => 'Thanh toán thành công.',
                'payment' => $payment
            ], 200);
        }

        // Ngủ một chút trước khi kiểm tra lại
        sleep(1); // Tạm dừng 1 giây trước khi kiểm tra lại
    }

    // Trả về nếu hết thời gian chờ và thanh toán không thành công
    return response()->json([
        'message' => 'Thanh toán chưa thành công hoặc thời gian chờ đã hết.'
    ], 400);
}
}