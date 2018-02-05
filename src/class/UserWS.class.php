<?php

/**
 * Class UserWS
 */
class UserWS
{
    const VAR_ID = 'id';

    /**
     * Execute le web service
     *
     * @param string $methode
     *          La méthode demandée (GET | POST | PUT | DELETE)
     */
    public static function execute($method)
    {
        // On test si on fait une requête en POST
        if ($method == "POST") {
            // On fait un POST sur index.php
            $id = UserWS::doPost();
            if ($id === FALSE) {
                // HTTP 400 : Bad Request
                http_response_code(400);
            } else {
                // HTTP 201 : Created
                http_response_code(201);
                // On retourne l'identifiant du nouvel utilisateur
                echo json_encode($id);
            }
        } else if ($method == "GET") {
            // On fait un GET sur index.php
            if (!UserWS::doGet()) {
                // HTTP 400 : Bad Request
                http_response_code(404);
            }
        } else if ($method == "PUT") {
            // On fait un PUT sur index.php
            $result = UserWS::doPut();
            if ($result === -1) {
                // HTTP 400 : Bad Request
                http_response_code(400);
            } else if ($result === FALSE) {
                // HTTP 404 : Not found
                http_response_code(404);
            } else if ($result === TRUE) {
                // HTTP 202 : Accepted
                http_response_code(202);
            }
        } else if ($method == "DELETE") {
            // On fait un DELETE sur index.php
            $result = UserWS::doDelete();
            if ($result === -1) {
                // HTTP 400 : Bad Request
                http_response_code(400);
            } else if ($result === FALSE) {
                // HTTP 404 : Not found
                http_response_code(404);
            } else if ($result === TRUE) {
                // HTTP 202 : Accepted
                http_response_code(202);
            }
        } else {
            // HTTP 405 : Method Not Allowed
            http_response_code(405);
        }
    }

    /**
     * Fonction appelée lors d'un GET
     */
    private static function doGet()
    {
        if (!isset($_GET[UserWS::VAR_ID])) {
            // Pas d'identifiant on retourne le tableau
            echo json_encode(UserDao::getUsers());

            return true;
        } else {
            $user = UserDao::getUserById($_GET[UserWS::VAR_ID]);
            if ($user != false) {
                // L'utilisateur demandé existe
                echo json_encode($user);

                return true;
            }
        }
        // L'utilisateur demandé n'existe pas
        return false;
    }

    /**
     * Fonction appelée lors d'un POST
     * Elle retourne l'identifiant de
     */
    private static function doPost()
    {
        /*
         * On récupére le contenu de la requête HTTP
         * qui contient la chaîne Json représentant l'utilisateur
         */
        $json = file_get_contents('php://input');
        // On transforme la chaîne Json en objet
        $user = User::fromJson($json);
        if ($user !== false) {

            // On retourne l'identifiant du nouvel utilisateur
            return UserDao::createUser($user);
        }

        return false;
    }

    /**
     * Fonction appelée lors d'un PUT
     */
    private static function doPut()
    {
        /*
         * On récupére le contenu de la requête HTTP
         * qui contient la chaîne Json représentant l'utilisateur
         */
        $json = file_get_contents('php://input');
        // On transforme la chaîne Json en objet
        $user = User::fromJson($json);
        // Si la chaîne ne contient pas un objet User
        if (!$user) {

            return -1;
        }

        // On transforme la chaîne Json en objet
        return UserDao::updateUser($user);
    }

    /**
     * Fonction appelée lors d'un DELETE
     */
    private static function doDelete()
    {
        // On récupére l'identifiant utilisateur
        $id = json_decode(file_get_contents('php://input'));
        // On test la présence de l'identifiant
        if (!isset($id) && !is_numeric($id)) {
            return -1;
        }
        // On efface l'utilisateur
        return UserDao::deleteUser($id);
    }
}