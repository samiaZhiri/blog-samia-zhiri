<?php

/**Le composant Illuminate Database est une boîte à outils complète de base de données pour PHP, fournissant un générateur de requête expressif, un ORM de style ActiveRecord et un générateur de schéma. Il prend actuellement en charge MySQL, Postgres, SQL Server et SQLite. Il sert également de couche de base de données du framework PHP Laravel. */


use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

//Créez une nouvelle instance de gestionnaire "Capsule"
//Capsule vise à rendre la configuration de la bibliothèque 
$capsule = new Capsule;


$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => '3308',
    'database' => 'blog_php_sz',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8'
    // 'collation' => 'utf8_unicode_ci',
    // 'prefix' => '',
]);
$capsule->setEventDispatcher(new Dispatcher(new Container));

$capsule->setAsGlobal();
// Configurez l'ORM Eloquent...
$capsule->bootEloquent();
