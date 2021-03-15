<?php


namespace App\Http\Controllers\ApiGateway\Core;


use App\Http\Controllers\Controller;
use App\Http\Requests\Core\IndexCommentsForMerchantRequest;
use App\Http\Requests\Core\StoreMerchantRequestComment;
use App\Http\Requests\Core\UpdateCommentRequest;
use App\Modules\Core\Models\Comment;
use App\Services\Core\ServiceCore;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index(IndexCommentsForMerchantRequest $request)
    {
        $comments = Comment::query()
            ->filterRequest($request);

        if ($request->query('object') == 'true') {
            return $comments->first();
        }
        return $comments->paginate($request->query('per_page'));
    }

    public function store(StoreMerchantRequestComment $request)
    {
        $comment = new Comment();
        $comment->body = $request->input('body');
        $comment->commentable_type = $request->input('commentable_type');
        $comment->commentable_id = $request->input('commentable_id');
        $comment->save();

        $user = ServiceCore::request('GET', 'users', new Request([
            'id' => $comment->created_by_id,
            'object' => 'true'
        ]));

        $comment->created_by = $user;
        return $comment;
    }

    public function update($id, UpdateCommentRequest $request)
    {
        $comment = Comment::with('created_by')->findOrFail($id);
        if (!$comment->fresh || $comment->created_by_id != auth()->id()) {
            return response()->json(['message' => 'Комментарий не может быть изменен'], 400);
        }

        $comment->body = $request->input('body');
        $comment->save();

        return $comment;
    }

    public function destroy($id)
    {
        $comment = Comment::query()->findOrFail($id);
        if (!$comment->fresh || $comment->created_by_id != auth()->id()) {
            return response()->json(['message' => 'Комментарий не может быть изменен'], 400);
        }
        $comment->delete();
        return response()->json(['message' => 'Успешно удалено']);
    }
}
