<?php

namespace App\Controllers;

use App\Models\Comment;

class CommentController
{
    public function delete($id, $postId)
    {
        Comment::destroy($id);
        return redirect('home.show', ['id' => $postId]);
    }
}
