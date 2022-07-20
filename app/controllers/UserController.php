<?php

namespace App\Controllers;

use Controller;
use App\Models\User;
use App\https\HttpRequest;

class UserController extends Controller
{
    public function store()
    {
        return $this->view('users.store');
    }

    public function register()
    {
        return $this->view('users.register');
    }
    //L'inscription d'un utilisateur
    public function signup(HttpRequest $request)
    {
        $value = $request->validator([
            'username' => ['required'],
            'password' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required']
        ]);
        //Hacher le mot de passe
        $value['password'] = password_hash($value['password'], PASSWORD_DEFAULT);

        $value['role'] = 0;
        User::create($value); //$value possède les données avec pour chaque index les valeurs insérées
        return redirect('users.store');
    }

    public function login(HttpRequest $request)
    {
        $value = $request->validator([
            'username' => ['required', 'max:10'],
            'password' => ['required']
        ]);

        //Je vérifie que le username ds la bdd correspond avec le champs username  -> on le récupère
        $user = User::where('username', '=', $request->name('username'))->first();

        //Faut vérifier si le username existe déja
        if (!$user) {
            die("L'utilisateur et/ou le mot de passe est incorrect");
        }
        //ici on a un user existant, on peut vérifier le mot de passe et le connecter 
        //vérifier le mot de passe
        if ($user !== null) {
            if (password_verify($value["password"], $user["password"])) {
                // var_dump($user->role);
                // die();
                //je vais stocker le role du user pour savoir si c'est un admin ou pas
                $request->session('auth', (int) $user->role);
                //je stocke le nom du user pour voir qu'il est connecté
                $request->session('username', $user->username);
                //je stocke l'id du user
                $request->session('id', $user->id);
                header('Location: /');
            }
        }


        // //..      
        // if ($user !== null) {
        //     //vérifier le mot de passe
        //     if ($request->name('password') === $user->password) {
        //         //je vais stocker le role du user pour savoir si c'est un admin ou pas

        //         //redirection vers la page d'accueil aprés la connection                
        //         header('Location: /');
        //     } else {
        //         echo "Mot de passe incorrect";
        //     }
        // } else {
        //     //Si il n'est pas connecté je le redirige vers l'ancienne url
        //     //avec la fonction lastUrl ds HttpRequest
        //     $request->lastRedirect();
        // }
    }
    //Supprimer toutes les données enregistrées en session
    public function logout()
    {
        session_destroy();
        return redirect('home.index');
    }
}
