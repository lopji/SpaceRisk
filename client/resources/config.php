<?php

namespace resources;

/**
 * Configuration
 *
 * Gestion des différentes constantes du site web
 *
 * @category  resources
 * @package   resources
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @example   ../index.php
 * @example <br />
 * config::DB_HOST;
 * @version   1.0
 * @since     inconnue
 * @author    Julien Rochat
 */
class config {

    /**
     * Contient l'adresse IP de la base de données
     * @const DB_HOST
     */
    const DB_HOST = '127.0.0.1';

    /**
     * Contient le nom de compte de la base de données
     * @const DB_USERNAME
     */
    const DB_USERNAME = 'root';

    /**
     * Contient le mot de passe de la base de données
     * @const DB_PASSWORD
     */
    const DB_PASSWORD = '';

    /**
     * Contient le nom de la base de données
     * @const DB_NAME
     */
    const DB_NAME = 'spacerisk';

}
