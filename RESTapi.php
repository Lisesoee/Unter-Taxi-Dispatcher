<?php
/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 22-05-2017
 * Time: 14:29
 */
include('Database.php');

$Database = new Database();

//get the http method, path and body of request:
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

//We get the table and key
$table = array_shift($request);
$key = array_shift($request);


switch ($method){
    case 'GET':
        $sql = "SELECT * FROM `$table`";
        //$sql = "SELECT * FROM `$table`".($key?" WHERE id =$key":``);
        echo "Get some echo";
        break;
    case 'PUT':
        break;
    case 'POST':
        break;
    case 'DELETE':
        $sql = "DELETE FROM `$table` WHERE Request_ID =$key";
        break;
}

//Execute sql statement
$result = $Database -> doSelect($sql);
//$result = $Database -> doExecuteQuery($sql);

echo "some more stuff";

if (is_array($result)){
    $i=0;
    foreach ($result as $item) {
        $i++;
        //echo $result["FName"];
        //echo $result["LName"];
    }
    echo json_encode($result);
}



if ($method == 'GET') {
    if (!$key) echo '[';
    for ($i = 0; $i < mysqli_num_rows($result); $i++)
    {
        echo ($i > 0 ? ',' : '') . json_encode(mysqli_fetch_object($result));
    }
    if (!$key) echo ']';
}

