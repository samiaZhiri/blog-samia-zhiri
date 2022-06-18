<?php

namespace App\Controllers;

use App\https\HttpRequest;
use Controller;
use App\Models\Contact;

class ContactController
{

    public function send()
    {
        if (isset($_POST['btn'])) {
            // $name = $request->name('name');
            // $email = $request->name('email');
            // $message = $request->name('message');

            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];

            $mailTo = 'samzhiri15@gmail.com';
            $headers = "From : " . $email;
            $text = "Vous avez reçu un email de " . $name . " .\n\n" . $message;

            mail($mailTo, $headers, $text);
            header('Location: /');
        }
    }
}
