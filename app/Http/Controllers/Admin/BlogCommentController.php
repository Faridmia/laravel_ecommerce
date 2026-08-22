<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogComment;

class BlogCommentController extends Controller
{
    public function list()
    {
        $data['getRecord'] = BlogComment::with('blog')
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->paginate(15);
        $data['header_title'] = 'Blog Comments';
        return view('admin.blog_comment.list', $data);
    }

    public function updateStatus($id, $status)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->status = intval($status);
        $comment->save();

        return redirect()->back()->with('success', 'Comment status updated successfully');
    }

    public function delete($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->is_delete = 1;
        $comment->save();

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }
}
