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
        $comments = Comment::where('post_id', $id)->get();
        return $this->view('home.show', compact('post', 'comments'));
    }
    public function create(HttpRequest $request)
    {
    }
    public function cmtcreate(HttpRequest $request)
    {
        $id = $request->name('post_id');
        $feilds = $request->name();
        Comment::create($feilds);
        return redirect('home.show', ['id' => $id]);
    }
    public function delete($id)
    {
        Post::destroy($id);
        return redirect('home.index');
    }
}
