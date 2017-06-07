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
    public function decrementCustomer(){

    }



}


if ($_SERVER['REQUEST_METHOD'] == 'POST') //if this is the first time, it does nothing
{
    //TODO: could be in a method in the class instead of here:
    $customerID = htmlspecialchars($_POST['customerID']);

    $ch = curl_init('http://87.54.141.140/WebService/RESTApi.php/decrementCustomer/'.$customerID);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    echo "Customer with id ".$customerID." has been down-prioritized.";
}





/**
 * Following code is for creating the page, setting the different parts of the html document, and displaying the page.
 */
$manageCustomersPage = new ManageCustomersPage();

//We set the body/content of the page:
$manageCustomersPage->pageContent = "

<body>
<div style = 'position: relative'>
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