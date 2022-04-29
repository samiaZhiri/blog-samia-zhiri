<?php

use App\https\HttpRequest;

function route($name, $params = [])
{
    $path = Route::url($name, $params);
    echo $path;
}

function redirect($name, $params = [])
{
    $path = Route::url($name, $params);
    header('Location:' . $path);
}
function setpost()
{
    return isset($_SESSION['input']) ? $_SESSION['input'] : "";
}
function session($val)
{
    return isset($_SESSION[$val]) ? $_SESSION[$val] : "";
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
