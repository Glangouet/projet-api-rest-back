<?php

/**
 * Class WebService
 */
class WebService
{
    const URL_VAR = "ws";
    const PHP_CLASS_DIR = "class";
    const PHP_WS_DIR = self::PHP_CLASS_DIR . "/ws";
    const PHP_CLASS_DAO = "Dao";
    const PHP_CLASS_WS = "WS";
    const PHP_CLASS_EXT = "class.php";
    const WS_EXEC_FCT = "execute";

    /**
     * Execute le Web Service
     * @param string $method
     * La méthode HTTP
     */
    public static function execute($method)
    {
        // On récupére le nom du Web Service
        $webService = WebService::getWebService();
        // Si le Web Service existe
        if (WebService::includeWebService($webService)) {
            // On prépare le nom de la fonction à appeler
            $toCall = ucfirst($webService) . self::PHP_CLASS_WS . "::" . self::WS_EXEC_FCT;
            // On l'appelle
            call_user_func($toCall, $method);

            return true;
        }
        return false;
    }

    /**
     *
     * Retourne l'identifiant contenu dans l'URL ou false
     *
     * @return string|boolean
     */
    public static function getId()
    {
        // On récupére l'URL dans la variable ws
        $url = $_GET[WebService::URL_VAR];
        // On vérifie si l'URL contient un '/' avec l'id
        $pos = strpos($url, '/');
        if ($pos > 0 && $pos != strlen($url) - 1) {
            // S'il y a un ID (eg. user/1) on le récupére
            $id = explode('/', $url) [1];
            // On retourne ce qu'il y a après le '/' si c'est de type numérique
            if (is_numeric($id)) {

                return $id;
            }
        }

        return false;
    }

    /**
     * Inclue les fichiers correspondant au Web Service demandé
     *
     * @param string $webService
     * @return boolean
     */
    private static function includeWebService($webService)
    {
        // On construit le chemin
        $dir = self::PHP_WS_DIR . '/' . $webService;
        // Si le chemin existe
        if (is_dir($dir)) {
            // On construit un tableau avec les trois fichiers
            $files = array();
            // le fichier class/user/User.class.php
            $files[] = $dir . '/' . ucfirst($webService) . '.' . self::PHP_CLASS_EXT;
            // le fichier class/user/UserDao.class.php
            $files[] = $dir . '/' . ucfirst($webService) . self::PHP_CLASS_DAO . '.' . self::PHP_CLASS_EXT;
            // le fichier class/user/UserWS.class.php
            $files[] = $dir . '/' . ucfirst($webService) . self::PHP_CLASS_WS . '.' . self::PHP_CLASS_EXT;
            foreach ($files as $file) {
                // Si le fichier existe, on l'inclue
                if (is_file($file)) {
                    include_once $file;
                } else {

                    // Si le fichier n'existe pas, on retourne false;
                    return false;
                }
            }
        } else {

            return false;
        }

        return true;
    }

    /**
     * Retourne le nom du Web Service demandé ou false
     *
     * @return string|boolean
     */
    private static function getWebService()
    {
        // On récupére l'URL dans la variable ws
        $url = $_GET[self::URL_VAR];
        // On vérifie si l'URL contient un '/' avec l'id
        if (strpos($url, '/') > 0) {

            // S'il y a un ID (eg. user/1)
            // On retourne ce qu'il y a avant le '/'
            return explode('/', $url) [0];
        }

        return strlen($url) ? $url : false;
    }
}

?>