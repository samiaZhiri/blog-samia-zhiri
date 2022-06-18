<?php

namespace App\Controllers;

use Controller;
use App\Models\Post;
use App\Models\Comment;
use App\https\HttpRequest;

class HomeController extends Controller
{
    public function welcome()
    {
        return $this->view('home.welcome');
    }
    public function index()
    {
        $queries = Post::orderBy('created_at', 'desc')->get();
        return $this->view('home.index', compact('queries'));
    }
    public function show($id)
    {
        $post = Post::find($id);
        //Afficher les commentaires validés par l'admin        
        $comments = Comment::where(['valid' => 1, 'post_id' => $id])->get();
        return $this->view('home.show', compact('post', 'comments'));
    }
    public function cmtcreate(HttpRequest $request)
    {
        $id = $request->name('post_id');
        $feilds = $request->validator([
            'user' => ['required'],
            'content' => ['required']
        ]);
        Comment::create($feilds);
        return redirect('home.show', ['id' => $id]);
    }
    public function delete(int $id)
    {
        if (isAdmin()) {
            Post::destroy($id);
            return redirect('home.index');
        } else {
            return redirect('users.store');
        }
    }
}
