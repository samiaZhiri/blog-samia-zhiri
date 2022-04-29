<?php

namespace App\https;

use BadFunctionCallException;

class HttpRequest
{
    protected $posts = [];
    protected $errors = [];

    public function __construct()
    {
        $this->posts = $this->name();
    }
    public function name(string $name = null)
    {
        if ($name == null) {
            return $_POST;
        } else {
            return $_POST[$name];
        }
    }
    public function session($name, $data = null)
    {
        if (!empty($data) | $data != null) {
            $_SESSION[$name] = $data;
        } else {
            return isset($_SESSION[$name]) ? $_SESSION[$name] : "";
        }
    }
    public function lasturl()
    {
        return $_SERVER['HTTP_REFERER'];
    }
    public function validator(array $rules)
    {
        foreach ($rules as $key => $valueArray) {
            if (array_key_exists($key, $this->posts)) {
                foreach ($valueArray as $rule) {
                    switch ($rule) {
                        case 'required':
                            $this->required($key, $this->posts[$key]); //c'est comme si j'écris $_POST['username']
                            break;
                        case substr($rule, 0, 3) === 'max':
                            $this->max($key, $this->posts[$key], $rule);
                            break;
                        case substr($rule, 0, 3) === 'min':
                            $this->min($key, $this->posts[$key], $rule);
                            break;
                        default:

                            break;
                    }
                    $this->session('input', $this->posts);
                }
            }
        }
        if ($this->getErrors() != null) {
            header('Location:' . $this->lastUrl());
        } else {
            unset($_SESSION['errors']);
            return $this->name();
        }
    }
    public function required($name, $value)
    {
        $value = trim($value);
        if (!isset($value) || is_null($value) || empty($value)) {
            $this->errors[$name][] = "<span class='text-danger'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> " . ucfirst($name) . " est requis</span>";
        }
    }
    public function max($name, $value, $rule)
    {
        preg_match_all('/(\d+)/', $rule, $matches);
        $limit = (int) $matches[0][0];
        if (strlen($value) > $limit) {
            $this->errors[$name][] = "<span class='text-danger'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> " . ucfirst($name) . " doit contenir le nombre de caractères inférieur ou égal à $limit</span>";
        }
    }
    public function min($name, $value, $rule)
    {
        preg_match_all('/(\d+)/', $rule, $matches);
        $limit = (int) $matches[0][0];
        if (strlen($value) < $limit) {
            $this->errors[$name][] = "<span class='text-danger'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> " . ucfirst($name) . " doit contenir le nombre de caractères supérieur ou égal à $limit</span>";
        }
    }
    public function getErrors()
    {
        if (!empty($this->errors)) {
            $_SESSION['errors'] = $this->errors;
        } else {
            session_destroy();
        }
        return isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
    }
}
