<?php

/**
 * Class UserDao
 */
class UserDao
{

    const USER_TAB = "users";
    const USER_ID = "user_id";
    const MEMCACHE_ADDRESS = "127.0.0.1";
    const MEMCACHE_PORT = 11211;

    /**
     * @param $id
     * @return bool
     */
    public static function getUserById($id)
    {
        $users = UserDao::getUsers();
        foreach ($users as $user) {
            if ($user->id == $id) {

                return $user;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public static function getUsers()
    {
        $users = UserDao::getMemcacheServer()->get(UserDao::USER_TAB);
        if (!isset($users) || !$users) {
            $users = array();
        }

        return array_values($users);
    }

    /**
     * @param User $user
     * @return array|int|string
     */
    public static function createUser(User $user)
    {
        // On récupére l'identifiant
        $id = UserDao::getCurrentId();
        // On le positionne
        $user->id = $id;
        // On récupére le tableau d'utilisateur
        $users = UserDao::getUsers();
        // On ajoute l'utilisateur
        $users [$id] = $user;
        // On sauvegarde le tableau
        UserDao::setUserTab($users);
        // On retourne l'identifiant
        return $id;
    }

    /**
     * @param User $user
     * @return bool
     */
    public static function updateUser(User $user)
    {
        $users = UserDao::getUsers();
        // On vérifie que l'utilisateur existe
        foreach ($users as $index => $tmp) {
            if ($tmp->id == $user->id) {
                // On le remplace
                $users[$index] = $user;
                // On sauvegarde le tableau
                UserDao::setUserTab($users);

                return true;
            }
        }

        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public static function deleteUser($id)
    {
        $users = UserDao::getUsers();
        // On vérifie que l'utilisateur existe
        foreach ($users as $index => $user) {
            if ($user->id == $id) {
                // On l'efface du tableau
                unset($users[$index]);
                // On sauvegarde le tableau
                UserDao::setUserTab($users);

                return true;
            }
        }

        return false;
    }

    /**
     * @param $users
     */
    private static function setUserTab($users)
    {
        UserDao::getMemcacheServer()->set(UserDao::USER_TAB, $users);
    }

    /**
     * Retourne l'identifiant du prochain utilisateur
     */
    private static function getCurrentId()
    {
        $user_id = UserDao::getMemcacheServer()->get(UserDao::USER_ID);
        if (!isset ($user_id) || !$user_id) {
            $user_id = 0;
            UserDao::getMemcacheServer()->set(UserDao::USER_ID, $user_id);
        }
        UserDao::getMemcacheServer()->increment(UserDao::USER_ID);

        return $user_id;
    }

    /**
     * Retourne le serveur memchache
     */
    private static function getMemcacheServer()
    {
        $memcache = new Memcache();
        $memcache->addserver(UserDao::MEMCACHE_ADDRESS, UserDao::MEMCACHE_PORT);

        return $memcache;
    }
}
