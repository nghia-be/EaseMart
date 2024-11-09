<?php
namespace App\Imports;

use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\StockTransactionDetail;
use App\Models\ProductUnit;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StockImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    { $row = array_map('trim', $row);
        // Tạo mã giao dịch
        $transaction_code = 'IM' . strtoupper(Str::random(8));

        // Tạo bản ghi StockTransaction chính
        $stockTransaction = StockTransaction::create([
            'transaction_code' => $transaction_code,
            'transaction_type' => 1, // loại giao dịch (1 có thể là nhập kho)
            'warehouse_code' => $row['warehouse_code'], // warehouse_code từ Excel
            'supplier_code' => $row['supplier_code'], // supplier_code từ Excel
            'note' => $row['note'] ?? '', // ghi chú nếu có
            'created_by' => auth()->id(), // người tạo giao dịch
        ]);

        // Xử lý từng sản phẩm trong Excel
        $product = [
            'id' => $row['product_id'], // product_id từ Excel
            'unit_code' => $row['unit_code'], // unit_code từ Excel
            'quantity' => $row['quantity'], // quantity từ Excel
        ];

        // Lấy thông tin `unit_code` hiện tại của sản phẩm
        $currentUnit = DB::table('product_units')
            ->where('product_id', $product['id'])
            ->where('unit_code', $product['unit_code'])
            ->first();
        
        if (!$currentUnit) {
            // Trả về lỗi nếu không tìm thấy đơn vị hợp lệ
            return response()->json([
                'success' => 'fail',
                'message' => 'Đơn vị không hợp lệ cho sản phẩm ' . $product['id']
            ]);
        }

        // Kiểm tra nếu sản phẩm chỉ có 1 `unit_code`
        $unitCount = DB::table('product_units')
            ->where('product_id', $product['id'])
            ->count();

        if ($unitCount === 1) {
            // Nếu chỉ có 1 `unit_code`, sử dụng `quantity` như là số lượng cần lưu mà không cần quy đổi
            $quantity = $product['quantity'];
            $lowestUnit = $currentUnit; // Đặt lowestUnit là currentUnit vì chỉ có 1 unit
        } else {
            // Tìm `unit_code` có `level` nhỏ nhất cho sản phẩm này
            $lowestUnit = DB::table('product_units')
                ->where('product_id', $product['id'])
                ->orderBy('level', 'desc') // Lấy cấp thấp nhất (highest level number)
                ->first();

            if (!$lowestUnit) {
                return response()->json([
                    'success' => 'fail',
                    'message' => 'Không tìm thấy đơn vị thấp nhất cho sản phẩm ' . $product['id']
                ]);
            }

            // Quy đổi số lượng nếu `currentUnit` không phải là cấp thấp nhất
            $quantity = $product['quantity'];
            if ($currentUnit->level < $lowestUnit->level) {
                $quantity *= $currentUnit->exchangrate / $lowestUnit->exchangrate;
            }
        }

        // Thêm bản ghi chi tiết giao dịch vào bảng StockTransactionDetail
        StockTransactionDetail::create([
            'product_transaction_id' => $stockTransaction->id,
            'product_id' => $product['id'],
            'unit_code' => $currentUnit->unit_code,
            'quantity' => $row['quantity'],
        ]);

        // Cập nhật hoặc tạo mới bản ghi trong bảng Stock với `product_unit_id` của cấp thấp nhất
        Stock::updateOrCreate(
            [
                'product_id' => $product['id'],
                'warehouse_code' => $row['warehouse_code'], // warehouse_code từ Excel
                'unit_code' => $lowestUnit->unit_code
            ],
            [
                'quantity' => DB::raw("quantity + $quantity"),
            ]
        );

        return $stockTransaction;
    }
}

