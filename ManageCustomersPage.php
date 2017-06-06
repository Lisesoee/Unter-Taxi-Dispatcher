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



}


if ($_SERVER['REQUEST_METHOD'] == 'PUT') //if this is the first time, it does nothing
{
    $customerID = htmlspecialchars($_PUT['customerID']);
    //var_dump($_POST);
    echo "Calling rest";

    callRESTApi('customer/'.$customerID);
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
<form action='javascript:void(0);' method='PUT' name='decrementCustomerPriorityForm'>
    <p>ID of customer: </p>
    <input type='text' name='customerID'> 
    <br>
    <input type='submit' value='Down-prioritize Customer' name='decrementPriorityBtn'>
</form>
</div>
</body>

";




//And finally, we display the page (after all the specific sections has been set)
$manageCustomersPage->DisplayPage();