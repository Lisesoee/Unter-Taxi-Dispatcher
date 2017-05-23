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
//In case of updating or posting, we need the new values:
$values = array_shift($request);

switch ($method)
{
    case 'GET':
        //TODO: make sure this works: (is the key really null if its not set in the request? - else we will have an invisible error)
        //If we dont specify ID, we get all instances:
        if ($key === null){
            $sql = "SELECT * FROM `$table`";
        }
        else{
            $sql = "SELECT * FROM `$table` WHERE id =$key;";
        }


        break;
    case 'PUT':
        //Syntax example: Update Customer set oldName = newName where id = 1;
        $sql = "UPDATE `$table` SET $values WHERE ID =$key;";
        break;

    case 'POST':
        //We use a switch to set the appropriate columns for the given table:
        switch ($table)
        {
            case 'Request':
                $columns = "From_Location, To_Location";
                break;

            case 'Customer':
                $columns = "FName, LName, PhoneNb, Preferred_Brand";
                break;

            case 'Order':
                $columns = "Estimated_Time, Estimated_payment";
                break;
        }
        //Syntax example: Insert into Customer (FName, LName, PhoneNb, Preferred_Brand) Values (Hans, Hansen, 1234, Honda);
        $sql = "INSERT INTO `$table` ($columns) VALUES (newvalue)";
        break;

    case 'DELETE':
        //TODO: when deleting customer, we just flip the active-boolean
        //We dont really use delete, but now it is here..
        $sql = "DELETE FROM `$table` WHERE ID =$key";
        break;
}

//Execute sql statement
//If the method is getting multiple values we call doSelect:
if ($method == 'GET' || $key == null){
    $result = $Database -> doSelect($sql);
}
else{
    //Everything else just needs executing:
    $result = $Database -> doExecuteQuery($sql);
}

//We set and encode the response
$response = json_encode($result);
echo $response;


/**
 * Stuff i didnt want to delete:
 *
 *
 * if (is_array($result)){
foreach ($result as $item) {
json_encode($item);
}
echo json_encode($result);
}

 *
 *
if ($method == 'GET') {
if (!$key) echo '[';
for ($i = 0; $i < mysqli_num_rows($result); $i++)
{
echo ($i > 0 ? ',' : '') . json_encode(mysqli_fetch_object($result));
}
if (!$key) echo ']';
}


 */

