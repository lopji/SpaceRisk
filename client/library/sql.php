<?php

namespace library;

use \PDO;
use \PDOException;
use resources\config;

/**
 * Base de données
 *
 * Permet de gérer la base de données 
 *
 * @category  library
 * @package   library
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @example   ../index.php
 * @example <br />
 * $sql = new sql(); <br />
 * $sql->query("select * from user");
 * @version   1.0
 * @since     inconnue
 * @author    Julien Rochat
 */
class sql {

    /**
     * Contient la connexion à la base de données
     * @var PDO
     */
    public static $PDO = null;

     /**
     * Permet de gérer une connexion unique à la base de données
     *
     *
     * @since     unknown
     * @author    Julien Rochat
     */
    public function myDatabase() {
        if (self::$PDO == null) {
            try {
                self::$PDO = new PDO('mysql:host=' . config::DB_HOST . ';'
                        . 'dbname=' . config::DB_NAME . '', config::DB_USERNAME, config::DB_PASSWORD);
            } catch (PDOException $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
        return self::$PDO;
    }

     /**
     * Permet de lancer une requête sql sur la base de données
     *
     * @param string $string La requête sql à exécuter
     * @param int &$id La valeur de l'id inséré dans la base de données
     *
     * @since     unknown
     * @author    Julien Rochat
     */
    public function query($string, &$id = 0) {
        $PDO = $this->myDatabase();
        $sql = $PDO->prepare($string);

        if (!$sql) {
            echo $string . '<br />';
            echo "\nPDO::errorInfo():\n";
            print_r($PDO->errorInfo());
        } else {
            $sql->execute();
            $id = $PDO->lastInsertId();
            return $sql->fetchAll();
        }
    }

}
