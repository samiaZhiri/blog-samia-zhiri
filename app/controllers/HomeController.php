<?php

namespace App\Controllers;

use Controller;
use App\Models\Post;
use App\Models\Comment;
use App\https\HttpRequest;

class HomeController extends Controller
{
    //Afficher la page welcome
    public function welcome()
    {
        $request = request();
        $message = $request->session("message");
        return $this->view('home.welcome', ["message" => $message]);
    }
    //Afficher tous les articles du plus récent au plus ancien
    public function index()
    {
        $queries = Post::orderBy('created_at', 'desc')->get();
        return $this->view('home.index', compact('queries'));
    }
    //Afficher un article par rapport à son id
    public function show($id)
    {
        //requête qui retrouve mon article correspondant à l'id
        $post = Post::find($id);
        //Afficher les commentaires validés par l'admin        
        $comments = Comment::where(['valid' => 1, 'post_id' => $id])->get();
        return $this->view('home.show', compact('post', 'comments'));
    }
    //Créer un commentaire par rapport à l'id de l'article
    public function cmtcreate(HttpRequest $request)
    {
        $id = $request->name('post_id');
        $feilds = $request->validator([
            // 'user' => ['required'],
            'content' => ['required']
        ]);

        if (isUser() || isAdmin()) {
            //récupére l'id de l'utilisateur connecté
            //et ajoute l'id dans la session
            $feilds['user_id'] = (int)$request->session('id');
            // var_dump($feilds);
            Comment::create($feilds);
            //je redirige le user sur la même page
            return redirect('home.show', ['id' => $id]);
        } else {
            return redirect('users.store');
        }
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
