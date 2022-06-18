<?php
session_start();
require '../vendor/autoload.php';

//Cette methode va se charger de l'execution de mes routes
Route::run();
