<?php

// Utilisé pour autoriser les appel Web Service en AJAX depuis toutes les adresses
header("Access-Control-Allow-Origin: *");
// Utilisé pour autoriser les méthodes GET, POST, PUT et DELETE
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
// On autorise les données dans le header
header("Access-Control-Allow-Headers: Content-Type");
// On positione l'encodage en UTF-8 sinon json_encode ne fonctionnera pas !
header('Content-Type: application/json; charset=utf-8');

// On inclut l'objet User ainsi que les classes nécessaires
include_once 'class/User.class.php';
include_once 'class/UserDao.class.php';
include_once 'class/UserWS.class.php';

/**
 * Fonction principale
 */
function main()
{
    // On récupére la méthode
    $method = $_SERVER['REQUEST_METHOD'];
    // Si la méthode est de type option on ne continue pas l'exécution du script
    // Cela arrive avec certains navigateurs
    if ($method == "OPTIONS") {
        echo "Hello options";
        header('Access-Control-Allow-Origin: *');

        exit();
    }
    // On appel notre Web Service
    try {
        UserWS::execute($method);
    } catch (Exception $e) {
        echo $e->getMessage();
        echo $e->getCode();
        echo $e->getFile();
        echo $e->getLine();
    }
}

// Lancement de l'application
main();