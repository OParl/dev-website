<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangeCommentStatusRequest;
use App\Http\Requests\Admin\EditCommentRequest;
use App\Model\Comment;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::with('post')->paginate(15);

        return view('admin.comments.index', compact('comments'));
    }

    public function status(ChangeCommentStatusRequest $request)
    {
        $comment = Comment::find($request->input('id'));

        $comment->status = $request->input('status');
        $comment->save();

        return response()->json(['status' => $comment->status]);
    }

    public function delete($id)
    {
        $comment = Comment::with('post')->findOrFail($id);

        $author    = $comment->author_name;
        $postTitle = $comment->post;

        $comment->delete();

        $message = "Der Kommentar von {$author} zu “{$postTitle}” wurde erfolgreich gelöscht.";

        return redirect()->back()->with('info', $message);
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        return view('admin.comments.edit', compact('comment'));
    }

    public function save(EditCommentRequest $request)
    {
        $comment = Comment::find($request->input('id'));

        $commentData = $request->except(['_token', 'id']);

        $comment->update($commentData);
        $comment->save();

        $message = "Der Kommentar von {$comment->author_name} zu {$comment->post->title} wurde erfolgreich bearbeitet.";

        return redirect()->route('admin.comments.index')->with('info', $message);
    }
}
