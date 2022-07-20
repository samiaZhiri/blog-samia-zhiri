<?php

namespace App\https;



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
    public function query(string $name, $default = null)
    {
        return $_GET[$name] ?? $default;
    }
    public function except(string $name, $data = [])
    { //Si ce tableau est vide
        if (!empty($data)) { //je verifie si la clé existe ds ce tableau
            if (array_key_exists($name, $data)) {
                unset($data[$name]);
                return $data;
            }
        } else {
            if (array_key_exists($name, $this->name())) {
                unset($_POST[$name]);
                return $_POST;
            }
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
    public function unsetSession($name)
    {
        if (is_array($name)) {
            for ($i = 0; $i < count($name); $i++) {
                //$name c'est un tableau avec 0 pour errors et 1 pour input
                //regardez la function redirect dans helpers
                $data = $name[$i];
                unset($_SESSION[$data]); //Si on a errors alors $data sera errors
            }
        } else {
            unset($_SESSION[$name]);
        }
    }
    public function lastUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    public function lastRedirect()
    {
        return header('Location: ' . $this->lastUrl());
    }
    public function loaderFiles($name, $file_dest, array $data)
    {
        //je récupère le nom 
        $file_name = $_FILES[$name]['name'];
        //je récupère l'extension
        $file_extension = strrchr($file_name, ".");
        //je récupère le temp
        $file_temp = $_FILES[$name]['tmp_name'];
        //mettre le folder de destination
        $file_dest = $file_dest . $file_name;
        //Si ds l'array nous trouvons l'extension
        if (in_array($file_extension, $data)) {
            if (move_uploaded_file($file_temp, $file_dest)) {
                return $file_dest;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    //*******************Validator **************************************/

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
        if (!empty($this->getErrors())) {

            header('Location: ' . $this->lastUrl());
            die;
        } else {
            $this->unsetSession('errors');
            return $this->name();
        }
    }
    public function required(string $name, string $value)
    {
        $value = trim($value);
        if (!isset($value) || is_null($value) || empty($value)) {
            $this->errors[$name][] = "<span class='text-danger'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> " . ucfirst($name) . " est requis</span>";
        }
    }
    public function max(string $name, string $value, string $rule)
    {
        preg_match_all('/(\d+)/', $rule, $matches);
        $limit = (int) $matches[0][0];
        if (strlen($value) > $limit) {
            $this->errors[$name][] = "<span class='text-danger'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> " . ucfirst($name) . " doit contenir le nombre de caractères inférieur ou égal à $limit</span>";
        }
    }
    public function min(string $name, string $value, string $rule)
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
            $this->session('errors', $this->errors);
            return ($this->session('errors') !== null) ? $this->session('errors') : [];
        } else {
            return [];
        }
    }
}
