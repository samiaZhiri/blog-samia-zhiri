<?php
session_start(); //démarrage de la session
require '../vendor/autoload.php';

//Cette methode va se charger de l'execution de mes routes
Route::run();
