<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function getComments()
{
    // Lấy tất cả bình luận cùng với thông tin người dùng
    $comments = Comment::where('status', 1)->with('customer')->get();

    // Nhóm các bình luận theo người dùng
    $groupedComments = $comments->groupBy(function ($comment) {
        return $comment->customer->id; // Nhóm theo user_id
    });

    // Chuyển đổi kết quả về định dạng mong muốn
    $result = $groupedComments->map(function ($customerComments, $userId) {
        $customer = $customerComments->first()->customer; // Lấy thông tin người dùng từ bình luận đầu tiên

        return [
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
            ],
            'comments' => $customerComments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'product_id' => $comment->product_id,
                    'rating' => $comment->rating,
                    'content' => $comment->content,
                    'status' => $comment->status,

                ];
            }),
        ];
    });

    return response()->json($result);
}


    // Thêm bình luận mới
    public function store(Request $request)
    { 
        try
        {
            if($request == null)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Input is null',
                ], 422);
            }
        // Xác thực dữ liệu đầu vào
        $request->validate ([
            'product_id' => 'required|exists:products,id', // Sản phẩm phải tồn tại
            // 'user_id' => 'required|exists:users,id', // Người dùng phải tồn tại
            'rating' => 'required|integer|min:1|max:5', // Điểm đánh giá từ 1 đến 5
            'content' => 'required|string|max:500', // Nội dung bình luận, tối đa 500 ký tự
        ]);

        $offensiveWords = ['đụ mẹ', 'ncc','dm','vl','cc','đụ má','ncl','vl','dmm','chết','mẹ','cặc','lồn']; // Thay bằng từ ngữ cần chặn

        // Kiểm tra từ ngữ cấm trong nội dung
        foreach ($offensiveWords as $word) {
            if (stripos($request->input('content'), $word) !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nội dung chứa từ ngữ không phù hợp.',
                ], 422);
            }
        }

        $comment = Comment::create([
            'product_id' => $request->input('product_id'),
            'customer_id' => Auth::id(),
            'rating' => $request->input('rating'),
            'content' => $request->input('content'),
            // 'updated_at' => null,
        ]);
        
        return response()->json(['message' => 'Bình luận đã được thêm thành công!', 'comment' => $comment], 200);
        }catch (ValidationException $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xác thực.',
                'errors' => $e->validator->errors(), // Trả về lỗi xác thực
            ], 422);
        }
        
    }

    // Sửa bình luận
    public function update(Request $request, $id)
{
    // Xác thực dữ liệu đầu vào
    $request->validate([
        'rating' => 'integer|min:1|max:5', // Điểm đánh giá
        'content' => 'string|max:1000', // Nội dung bình luận
    ]);

    // Lấy bình luận theo ID và kiểm tra quyền sửa
    $comment = Comment::where('id', $id)
                      ->where('customer', Auth::id()) // Chỉ cho phép người dùng sửa bình luận của mình
                      ->first();
    
    // Kiểm tra xem bình luận có tồn tại hay không
    if (!$comment) {
        return response()->json(['message' => 'Bình luận không tồn tại hoặc bạn không có quyền sửa bình luận này.'], 404);
    }

    // Kiểm tra xem người dùng đã sửa bình luận này chưa
    if ($comment->updated_by) {
        return response()->json(['message' => 'Bạn chỉ được sửa bình luận một lần.'], 403);
    }

    // Cập nhật bình luận
    $comment->rating = $request->input('rating', $comment->rating);
    $comment->content = $request->input('content', $comment->content);
    $comment->updated_by = Auth::id(); // Ghi nhận người dùng đã sửa
    $comment->save();

    return response()->json([
        'message' => 'Bình luận đã được cập nhật thành công!',
        'data' => $comment,
    ], 200);
}


}
