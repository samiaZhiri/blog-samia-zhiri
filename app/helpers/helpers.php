<?php

use App\https\HttpRequest;

function request($instance = null)
{
    if ($instance == null) {
        $instance = new HttpRequest();
    }
    return $instance;
}
function route($name, $params = [])
{
    request()->unsetSession(['errors', 'input']);
    $path = Route::url($name, $params);
    echo $path;
}
// function route($name, $params = [])
// {
//     unset($_SESSION['errors']);
//     unset($_SESSION['input']);
//     $path = Route::url($name, $params);
//     echo $path;
// }
function redirect($name, $params = [])
{
    request()->unsetSession(['errors', 'input']);
    $path = Route::url($name, $params);
    header('Location:' . $path);
}
// function redirect($name, $params = [])
// {
//     unset($_SESSION['errors']);
//     unset($_SESSION['input']);
//     $path = Route::url($name, $params);
//     header('Location:' . $path);
// }
function setpost() //je veux garder en mémoire les input
{
    return isset($_SESSION['input']) ? $_SESSION['input'] : "";
}
function session($val)
{
    return isset($_SESSION[$val]) ? $_SESSION[$val] : [];
}

function Errors()
{

    $errors = session('errors');
    $dataErrors = [];

    if (!empty($errors)) {
        foreach ($errors as $key => $value) {
            $dataErrors = array_merge_recursive($dataErrors, array($key => implode($value)));
        }
    }
    return isset($dataErrors) ? $dataErrors : "";
}

function Auth()
{
    $request = request();
    return array(
        'status' => $request->session('auth'), //pareil que $_SESSION('auth')
        'username' => $request->session('username')
    );
}

function isAdmin()
{
    $request = request();
    if ($request->session('auth') === 1) {
        return true;
    } else {
        //sinon je le renvoi sur la page formulaire login
        return redirect('users.store');
    }
}
