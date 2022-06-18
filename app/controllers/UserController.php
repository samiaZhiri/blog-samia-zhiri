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

        //Je connecte le user avec son username et son mot de passe 
        $user = User::where('username', '=', $request->name('username'))->first();

        //Faut vérifier si le username existe déja
        if (!$user) {
            die("L'utilisateur et/ou le mot de passe est incorrect");
        }
        //ici on a un user existant, on peut vérifier le mot de passe et le connecter         
        if (password_verify($value["password"], $user["password"])) {
            header('Location: /');
        }

        //..      
        if ($user !== null) {
            if ($request->name('password') === $user->password) {
                //je vais stocker le role du user 
                $request->session('auth', (int) $user->role);
                $request->session('username', $user->username);
                $request->session('id', $user->id);
                //redirection vers la page d'accueil aprés la connection                
                header('Location: /');
            } else {
                echo "Mot de passe incorrect";
            }
        } else {
            //Si il n'est pas connecté je le redirige vers l'ancienne url
            //avec la fonction lastUrl ds HttpRequest
            $request->lastRedirect();
        }
    }
    //Supprimer toutes les données enregistrées en session
    public function logout()
    {
        session_destroy();
        return redirect('home.index');
    }
}
