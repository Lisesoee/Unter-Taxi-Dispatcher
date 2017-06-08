<?php

/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 06-06-2017
 * Time: 22:43
 */
require("HomePage.php");

class ManageCustomersPage extends HomePage
{
    public $decrementSucceedComment ='';


    /**
     * This function calls the RESTApi with a request for decrementing the priority of the given customer.
     * The method also sets the comment for the page that will show whenever the form is submitted.
     * @param $customerID
     */
    public function decrementCustomer($customerID){
        $ch = curl_init('http://87.54.141.140/TechicalServices/RESTApi.php/decrementCustomer/'.$customerID);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $this->decrementSucceedComment = "Customer with id ".$customerID." has been down-prioritized.";
    }
}

//We initialize the page:
//Note: this needs to be above the POST-code
$manageCustomersPage = new ManageCustomersPage();

//If it is the first time, it does nothing. If form is submitted, it decrements the given customer:
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $customerID = htmlspecialchars($_POST['customerID']);
    $manageCustomersPage->decrementCustomer($customerID);
}

$manageCustomersPage->additionalLinks ="
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/customStyles.css\">";

//We set the body/content of the page:
$manageCustomersPage->pageContent = "

<body>
<div class=\"flex-container\" style = 'position: relative'>".$manageCustomersPage->decrementSucceedComment."
<form action='ManageCustomers.php' method='POST' name='decrementCustomerPriorityForm'>
    <p>ID of customer: </p>
    <input type='text' name='customerID'> 
    <br>
    <input type='submit' value='Down-prioritize Customer' name='decrementPriorityBtn'>
</form>
</div>
</body>

";

//And finally, we display the page using the super-function (after all the specific sections has been set)
$manageCustomersPage->DisplayPage();

?>