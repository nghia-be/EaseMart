<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use Illuminate\Validation\Rule;
use App\Http\Controllers\BaseController;
use App\DataTables\Admin\CommentDataTable;
use App\DataTables\Admin\commentsDataTable;
use App\Models\Customers;

class CommentController extends BaseController
{
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách bình luận';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CommentDataTable $dataTable)
    {   
        return $dataTable->render('comment.index');
    }

    public function show(Request $request)
{
    $comment = Comment::findOrFail($request->id);
    $product = Products::find($comment->product_id);
    $customer = Customers::find($comment->customer_id);

    return response()->json([
        'comment' => $comment,
        'customer_name' => $customer ? $customer->name : null,
        'product_name' => $product ? $product->name : null,
        'rating' => $comment->rating,
        'content' => $comment->content,
    ]);
}

    
    public function destroy(Request $request)
    {
        $comment = Comment::findOrFail($request->id);
        $comment->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
    public function changeStatus(Request $request)
    {
        $comment = Comment::findOrFail($request->id);
        $comment->status == 0 ? $comment->status =1 : $comment->status = 0;
        $comment->save();
        $this->addToLog(request());
        return response()->json(['message' => 'Thay đổi thành công!']);
    }
}
