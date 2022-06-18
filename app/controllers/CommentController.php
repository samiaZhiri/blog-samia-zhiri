<?php

namespace App\Controllers;

use App\Models\Comment;

class CommentController
{
    // public function deleteComment($id, $postId)
    // {
    //     if (isAdmin()) {
    //         Comment::destroy($id);
    //         return redirect('home.show', ['id' => $postId]);
    //     }
    // }
    public function deleteComment($id)
    {
        if (isAdmin()) {
            Comment::destroy($id);
            return redirect('home.show');
        }
    }
}
