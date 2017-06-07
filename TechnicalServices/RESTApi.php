<?php
/**
 * Created by PhpStorm.
 * User: LiseMusen & Rayan El Hajj
 * Date: 22-05-2017
 * Time: 14:29
 * new IP Add: 87.54.141.140
 */
include('Persistence/Database.php');

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
        //If there is no id, we get all instances:
        if ($key == null) {
            $sql = "SELECT * FROM `$table`";
        } else {
            $sql = "SELECT * FROM `$table` WHERE id =$key;";
        }


        break;
    case 'PUT':
        switch($table){
            case 'mode':

                //We reset the modes
                $clearModesSql = "UPDATE `mode` SET is_Selected=0";
                $Database->doExecuteQuery($clearModesSql);

                //And set the values to flip the boolean:
                $values = "is_Selected = 1";
                echo $values;
                echo $table.$key;
                break;
        }
        //Syntax example: Update Customer set Name = newName where id = 1;
        $sql = "UPDATE `$table` SET $values WHERE ID =$key;";
        break;

    case 'POST':
        $decodedContent = json_decode($content, true);

        if (is_array($decodedContent)) {
            //We use a switch to set the appropriate columns for the given table:
            switch ($table) {
                case 'request':
                    $columns = "FK_customer_ID, From_Location, To_Location";
                    $customer_ID = $decodedContent['FK_Customer_ID'];
                    $from_Location = $decodedContent['From_Location'];
                    $to_Location = $decodedContent['To_Location'];

                    $values = $customer_ID . ',\'' . $from_Location . '\',\'' . $to_Location . '\'';
                    break;

                case '_customer':
                    $columns = "FName, LName, PhoneNb, Preferred_Brand, FK_Credentials_ID";

                    $credentials = $decodedContent["Credentials"];
                    $customer = $decodedContent["Customer"];


                    $credentials = json_encode($credentials);
                    $ch = curl_init('http://localhost/RESTApi.php/credentials/');
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $credentials);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    $result = curl_exec($ch);
                    curl_close($ch);

                    $select_cred_sql = "SELECT * FROM `credentials` WHERE ID=(SELECT MAX(ID) FROM `credentials`);";

                    $insertedCred_ID = $Database->doSelect($select_cred_sql);

                    foreach ($insertedCred_ID as $item) {
                        $FK_Credentials_ID = $item['ID'];
                    }

                    $firstName = $customer['FName'];
                    $lastName = $customer['LName'];
                    $phoneNb = $customer['PhoneNb'];
                    $preferredBrand = $customer['Preferred_Brand'];

                    $values = '\'' . $firstName . '\',\'' . $lastName . '\',\'' . $phoneNb . '\',\'' . $preferredBrand . '\',' . $FK_Credentials_ID;

                    //echo $values; //for debugging purposes

                    break;

                case 'credentials':

                    if ($key == 'validation') {
                        $username = $decodedContent['Username'];
                        $password = $decodedContent['Password'];
                        $validation_sql = 'SELECT * FROM `credentials` WHERE Username=' . '\'' . $username . '\' AND Password=\'' . $password . '\'';
                        $result = $Database->doSelect($validation_sql);
                    } else {

                        $columns = "Email, Username, Password";

                        $email = $decodedContent['Email'];
                        $username = $decodedContent['Username'];
                        $password = $decodedContent['Password'];

                        $values = '\'' . $email . '\',\'' . $username . '\',\'' . $password . '\'';
                        //echo $values; //for debugging purposes
                    }

                    break;

                case '_order':
                    $columns = "Estimated_Time, Estimated_Payment, FK_Request_ID, FK_Taxi_ID";
                    $estimated_Time = $decodedContent['Estimated_Time'];
                    $estimated_Payment = $decodedContent['Estimated_Payment'];
                    $request_ID = $decodedContent['FK_Request_ID'];
                    $taxi_ID = $decodedContent['FK_Taxi_ID'];

                    //We flip the booleans:
                    $flipRequestBoolean = "UPDATE `request` SET isDispatched = TRUE WHERE ID =$request_ID";
                    $Database ->doExecuteQuery($flipRequestBoolean);
                    $flipTaxiBoolean = "UPDATE `taxi` SET isAvailable = FALSE WHERE ID =$taxi_ID";
                    $Database ->doExecuteQuery($flipTaxiBoolean);

                    $values = $estimated_Time . ',\'' . $estimated_Payment . '\',' . $request_ID . ',' . $taxi_ID;
                    //echo $values; //for debugging purposes
                    $mailer = new Mailer();
                    $mailer->sendMail("notruth500@gmail.com", "helloo", "it worksss");
                    break;

            }

            if ($key != 'validation') {
                //Syntax example: Insert into Customer (FName, LName, PhoneNb, Preferred_Brand) Values (Hans, Hansen, 1234, Honda);
                $sql = "INSERT INTO `$table` ($columns) VALUES ($values)";
            }

            //echo $sql; //for debugging purposes
        } else {
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
if ($method == 'GET' && $key != 'validation') {
    $result = $Database->doSelect($sql);
} else if ($method != 'GET' && $key != 'validation') {
    $result = $Database->doExecuteQuery($sql);
}


//We set and encode the response and send it
$response = json_encode($result);

echo $response;

