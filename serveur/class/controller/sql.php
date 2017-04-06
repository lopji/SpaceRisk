<?php

class sql {

    const DB_HOST = '127.0.0.1';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = '';
    const DB_NAME = 'spacerisk';

    public static $PDO = null;

    public function myDatabase() {
        if (self::$PDO == null) {
            try {
                self::$PDO = new PDO('mysql:host=' . self::DB_HOST . ';'
                        . 'dbname=' . self::DB_NAME . '', self::DB_USERNAME, self::DB_PASSWORD);
            } catch (PDOException $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
        return self::$PDO;
    }

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
