<?php
/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 22-05-2017
 * Time: 14:29
 */
include('Database.php');

$Database = new Database();

//We get the content of the http-body and trim it:
$content = file_get_contents("php://input");
$content = trim(file_get_contents("php://input"));

//We get the http-method (get, post, etc..)
$method = $_SERVER['REQUEST_METHOD'];

//We get the specific request URL and split it into chunks seperated by '/':
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

//We get the table and key from the chunks of the end of the URL
$table = array_shift($request);
$key = array_shift($request);


//TODO: delete:
//In case of updating or posting, we need the new values:
//$values = array_shift($request);


switch ($method) {
    case 'GET':
        //TODO: make sure this works: (is the key really null if its not set in the request? - else we will have an invisible error)
        //If there is no id, we get all instances:
        if ($key == null) {
            $sql = "SELECT * FROM `$table`";
        } else {
            $sql = "SELECT * FROM `$table` WHERE id =$key;";
        }


        break;
    case 'PUT':
        //Syntax example: Update Customer set oldName = newName where id = 1;
        $sql = "UPDATE `$table` SET $values WHERE ID =$key;";
        break;

    case 'POST':
        $decodedContent = json_decode($content,true);

        if (is_array($decodedContent)) {

            //We use a switch to set the appropriate columns for the given table:
            switch ($table) {
                case 'request':
                    $columns = "FK_Customer_ID, From_Location, To_Location";
                    $customer_ID = $decodedContent['FK_Customer_ID'];
                    $from_Location = $decodedContent['From_Location'];
                    $to_Location = $decodedContent['To_Location'];

                    $values =  $customer_ID.',\''.$from_Location.'\',\''.$to_Location.'\'';
                    echo $values;
                    break;

                case '_customer':
                    $columns = "FName, LName, PhoneNb, Preferred_Brand";
                    //$fName =

                    break;

                case '_order':
                    $columns = "Estimated_Time, Estimated_Payment, FK_Request_ID, FK_Taxi_ID";
                    $estimated_Time = $decodedContent['Estimated_Time'];
                    $estimated_Payment = $decodedContent['Estimated_Payment'];
                    $request_ID=$decodedContent['FK_Request_ID'];
                    $taxi_ID=$decodedContent['FK_Taxi_ID'];

                    $values= $estimated_Time.',\''.$estimated_Payment.'\','.$request_ID.','.$taxi_ID;
                    echo $values;
                    break;
            }

            //Syntax example: Insert into Customer (FName, LName, PhoneNb, Preferred_Brand) Values (Hans, Hansen, 1234, Honda);
            $sql = "INSERT INTO `$table` ($columns) VALUES ($values)";

            echo $sql;
        }else{
            echo "Incorrect json input: must be an array"; //prompting the error
        }

        break;

    case 'DELETE':
        //TODO: when deleting customer, we just flip the active-boolean
        //We dont really use delete, but now it is here..
        $sql = "DELETE FROM `$table` WHERE ID =$key";
        break;
}

//Execute sql statement
if ($method == 'GET') {
    $result = $Database->doSelect($sql);
} else {
    $result = $Database->doExecuteQuery($sql);
}


//We set and encode the response
$response = json_encode($result);
echo $response;




