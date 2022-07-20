<?php

namespace App\Controllers;

use Controller;
use App\Models\Post;
use App\Models\Comment;
use App\https\HttpRequest;


class AdminController extends Controller
{
    //Affiche le formulaire pour créer un article
    public function store()
    {
        return $this->view('admin.store');
    }

    //Affiche les articles dans le tableau admin
    public function index()
    {
        $this->denyAccessUnlessAdmin();
        $queries = Post::orderBy('created_at', 'desc')->get();
        return $this->view('admin.index', compact('queries'));
    }

    //Supprime les articles dans le tableau admin
    public function delete(int $id)
    {
        $this->denyAccessUnlessAdmin();
        Post::destroy($id);
        return redirect('admin.index');
    }


    //Création d'un article
    public function create(HttpRequest $request)
    {
        $this->denyAccessUnlessAdmin();
        //Traitement de l'image
        $image = $request->loaderFiles('imagefile', 'assets/img/loaders/', ['.png', '.PNG', '.jpg', '.JPG', '.jpeg', 'JPEG']);
        //Récupérer mes champs
        $value = $request->validator([
            'title' => ['required'],
            'content' => ['required']

        ]);
        // Combine un ou plusieurs tableaux ensemble
        //renvoi un seul tableau et une seule requete
        $data = array_merge_recursive($value, ['img' => '/' . $image]);
        //récupére l'id de l'utilisateur en session
        //et ajoute l'id dans la session
        $data['user_id'] = $request->session('id');
        Post::create($data);
        return redirect('home.index');
    }

    //Affiche l'article par rapport à son id
    public function show($id)
    {
        $this->denyAccessUnlessAdmin();
        $post = Post::find($id);
        return $this->view('admin.show', compact('post'));
    }

    //Modifier un article
    public function update(HttpRequest $request, $id)
    {
        $this->denyAccessUnlessAdmin();
        //Traiter l'image
        $image = $request->loaderFiles('imagefile', 'assets/img/loaders/', ['.png', '.PNG', '.jpg', '.JPG', '.jpeg', 'JPEG']);
        //Récupérer mes champs
        $values = $request->validator([
            'title' => ['required'],
            'content' => ['required']
        ]);

        if ($image !== null) {
            //comme le champs img je l'ai mis en hidden
            //il faut faire en sorte que je puisse l'extraire si l'image n'est pas null
            $values = $request->except('img', $values);
            $data = array_merge_recursive($values, ['img' => '/' . $image]);
            Post::where('id', '=', $id)->update($data);
            return redirect('home.show', ['id' => $id]);
        } else {
            Post::where('id', '=', $id)->update($values);
            return redirect('home.show', ['id' => $id]);
        }
    }
    //*****GESTION DES COMMENTAIRES*******/ 

    //Lister tous les commentaires dans le tableau admin
    public function listComment()
    {
        $this->denyAccessUnlessAdmin();
        $comments = Comment::orderBy('created_at', 'desc')->get();
        return $this->view('admin.listComment', ['comments' => $comments]);
    }

    //Valider les commentaires
    public function validComment($id)
    {
        $this->denyAccessUnlessAdmin();
        Comment::where('id', '=', $id)->update(['valid' => 1]);
        // return $this->view('admin.listComment', ['validComm' => $validComm]);
        return redirect('admin.listComment');
    }

    //Supprimer les commentaires dans le tableau admin
    public function deleteComment($id)
    {
        $this->denyAccessUnlessAdmin();
        Comment::destroy($id);
        return redirect('admin.listComment');
    }
}
