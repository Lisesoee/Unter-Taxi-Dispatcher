<?php
/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 22-05-2017
 * Time: 14:29
 */

$Database = new Database();

//get the http method, path and body of request:
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));


switch ($method){
    case 'GET':
       // $sql = "SELECT * FROM '$table'"."($key? WHERE id =$key":''); break;
}

