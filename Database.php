<?php

/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 22-05-2017
 * Time: 09:47
 *
 * TODO: consider changing to PDO to handle errors better
 */
class Database{
    // The database connection
    protected static $connection;

    /**
     * This method connects to the database
     * //TODO: should we close the connection again?
     * @return boo: We return the MySQLi object instance on success, or false if something went wrong
     */
    public function connect() {
        // Try and connect to the database
        if(!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file
            //(We chose to use an ini-file to avoid risking the information being shown on the web due to a server-error or the like.) This doesnt work for some reason..
            $config = parse_ini_file('./config.ini');
            //self::$connection = new mysqli($config['host'],$config['username'],$config['password'],$config['dbname']);
            self::$connection = new mysqli("86.52.212.76","DMU4","github","lise&rayuntertaxidb");
        }

        // If connection was not successful, we handle the error
        if(self::$connection === false) {
            // TODO: error screen, log, admin notification or something?
            return false;
        }
        return self::$connection;
    }

    /**
     * This method sends a query to the database
     * @param $query
     * @return mixed: The return will send the data or return false if an error happended
     */
    public function doExecuteQuery($query) {
        //We connect to the database
        $connection = $this -> connect();

        //We send our query the database
        $result = $connection -> query($query);

        return $result;
    }

    /**
     * This method selects rows from the database using SQL SELECT
     * @param $query
     * @return array|bool: returns array of rows or false if a mistake has happended
     */

    /**
     * @param $query
     * @return array|bool
     */
    public function doSelect($query) {
        //We create an array to fill with the rows
        $rows = array();
        //We send the query to the database:
        $result = $this -> doExecuteQuery($query);
        //If we dont get anything we return false:
        if($result === false) {
            return false;
        }

        //Loop that inserts the rows from the database into our array:
        //(Fetch_assoc: Fetch a result row as an associative array)
        while ($row = $result -> fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch the last error from the database
     * //TODO: delete if not used
     * @return string Database error message
     */
    public function error() {
        $connection = $this -> connect();
        return $connection -> error;
    }

    /**
     * Quote and escape value for use in a database query
     * //TODO: delete if not used
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value) {
        $connection = $this -> connect();
        return "'" . $connection -> real_escape_string($value) . "'";
    }

}