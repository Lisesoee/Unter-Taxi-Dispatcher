<?php
/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 16-05-2017
 * Time: 14:43
 */
include('Database.php');


?>

<html>
<head>
    <style>
        header {
            text-align: center;
            padding: 2%
        }

        footer {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            padding: 1rem;
            background-color: #efefef;
            text-align: center;
        }

        .flex-container {
            display: -webkit-flex;
            display: flex;
            background-color: transparent;
            width: auto;
            height: auto;
            margin: auto;
            align-items: flex-start;
            align-self: flex-start;
            align-content: flex-start;
        }

        .flex-item {
            background-color: transparent;
            width: auto;
            height: auto;
            margin: auto;
            align-self: flex-start;
            align-content: flex-start;
        }

        /*Adding some nice look and feel to the table rows in general: */
        tr:nth-child(odd) {
            background-color: # #99ccff;
        }
        tr:nth-child(even) {
            background-color: # #b3daff;
        }
        tr:hover {
            background-color: #0066ff;
        }


        /*Makes sure that the table header doesn't light up on hover like the other table-rows*/
        th {
            background-color: #fff;
        }

        /*Selected row in both tables are coloured:*/
        .selectedRequest, .selectedTaxi {
            background-color: #0066ff;
        }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        //This script adds functionality for selecting rows in the tables.
        //The 'selected...' classes are coloured differently using CSS and will be used when dispatching the selected taxi to the selected requests
        $(document).ready(function () {
            $('table tr').click(function () {

                //----REQUEST TABLE----
                //We check if its a request row:
                if ($(this).hasClass('requestRow')){
                    //If the request row is selected we unselect it:
                    if ($(this).hasClass('selectedRequest')){
                        $(this).removeClass('selectedRequest');
                    }
                    //Else, we add select to it:
                    else{
                        ($(this)).addClass('selectedRequest');
                    }
                }

                //----AVAILABLE TAXIS TABLE----
                //We check if its a taxi row:
                if ($(this).hasClass('taxiRow')) {
                    //If the row is selectet, we un-select it:
                    if ($(this).hasClass('selectedTaxi')) {
                        $(this).removeClass('selectedTaxi');
                    }
                    else {
                        //First we remove all selections (none or one) in the table
                        $('.selectedTaxi').removeClass('selectedTaxi');
                        //Then we add the selection to the clicked row:
                        ($(this)).addClass('selectedTaxi');
                    }
                }
            });
        });
    </script>

</head>

<header>
    <h1>Unter Dispatcher</h1>
    <img src="images/taxi.png" alt="taxi picture" style="width:150px;height:99px;"/>
</header>

<nav>
    //TODO: add navigation bar with home page. (Good design and allows for easy changes further on)
</nav>

<body>

<!--Tables: -->
<div class="flex-container" id="tableSection">
    <div class="flex-item">
        <!--Table comments:
        We use thead-tag to make column header look special.
        When using this, all tr-tags needs to be in a tbody-tag.-->
        <table id="requestTable">
            <thead>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Brand preff.</th>
                <th>Phone no</th>
                <th>From address</th>
                <th>To address</th>
                <th>Time</th>
            </tr>
            </thead>


            <tbody>

            <!--PHP-code for getting requests for request table:-->
            <?php

            /**
             * This function calls the RESTApi to place a HTTP request and do a CRUD function in the database
             *
             * @param $params: the parameters for specific operations
             * @return bool|mixed|string: returns the decoded response from the RESTApi
             */
            function callRESTApi($params){
                //We get the json-file containing all the requests:
                $response = file_get_contents('http://localhost:8080/RESTApi.php/'.$params);

                /**
                 * We need to decode the http-response so we can use and display it:
                 *  Note: by adding a second parameter, 'true' to the json_decode method we could get the json as an
                 *  associative array, which would allow for a different way of extracting the data, but we choose
                 *  to use the json as an object to make the code more readable, since we can just extract data using
                 *  the column names
                 */
                $response = json_decode($response);
                return $response;
            }


            $taxiRequests = callRESTApi('Request');
            if (is_array($taxiRequests))
            {
                foreach ($taxiRequests as $request) {
                    //We note the necessary information:
                    $customerID = $request -> FK_customer_ID;
                    $fromAddress = $request -> From_Location;
                    $toLocation = $request -> To_Location;
                    $time = $request -> TimeStamp;

                    //We get the given customer and decode the response:
                    $customer = callRESTApi('Customer/'.$customerID);

                    //Even though its only one customer, we still loop the array:
                    foreach ($customer as $thisCustomer){
                        $FName = $thisCustomer -> FName;
                        $LName = $thisCustomer -> LName;
                        $PreferredBrand = $thisCustomer -> Preferred_Brand;
                        $PhoneNb = $thisCustomer -> PhoneNb;

                        //For each request with the given customer, we echo to the table:
                        echo "<tr class = 'requestRow'>
                        <td>$FName</td>
                        <td>$LName</td>
                        <td>$PreferredBrand</td>
                        <td>$PhoneNb</td>
                        <td>$fromAddress</td>
                        <td>$toLocation</td>
                        <td>$time</td>
                        </tr>";
                    }
                }
            }
            

            /*
             * Old stuff: (directly from database, without json)
             if (is_array($response))
             {
                 $Database = new Database();

                 $selectRequestsSql = "SELECT `FK_customer_ID`,`From_Location`, `To_Location` FROM `Request`";
                 $requests = $Database->doSelect($selectRequestsSql);

                 //For each request we get the specific customer information and inputs it all into the table:
                 foreach ($requests as $request)
                 {
                     //NOTE: the customer id has to be in its own variable; if '$request[...]' is just appended to the
                     // select-statement, errors will happen, while just appending the variable seems to be okay...
                     $customer_ID = $request["FK_customer_ID"];
                     $selectCustomerSql = "SELECT `FName`,`LName`,`PhoneNb`,`Preferred_Brand` FROM `Customer` WHERE `Customer_ID` = " . $customer_ID;
                     $customers = $Database->doSelect($selectCustomerSql);

                     //Note: we need to check that it is an array to avoid errors
                     if (is_array($customers))
                     {
                         foreach ($customers as $customer)
                         {
                             //We input the request information in the html table:
                             echo "<tr>
                             <td>" . $customer["FName"] . "</td>
                             <td>" . $customer["LName"] . "</td>
                             <td>" . $customer["Preferred_Brand"] . "</td>
                             <td>" . $customer["PhoneNb"] . "</td>
                             <td>" . $request["From_Location"] . "</td>
                             <td>" . $request["To_Location"] . "</td>s
                             </tr>";
                         }
                     }
                 }
             }*/
            ?>
            </tbody>

        </table>
    </div>
    <div class="flex-item">
        <table id="availableTaxisTable">
            <thead>
            <tr>
                <th>Brand</th>
                <th>Licence plate</th>
                <th>Price per km</th>
            </tr>
            </thead>

            <tbody>

            <!--PHP code for adding available taxis to the second table:-->
            <?php
            $availableTaxis = callRESTApi('Taxi');

            //In case of no available taxis we check:
            if (is_array($availableTaxis))
            {
                //For each taxi we print it if it is available:
                foreach ($availableTaxis as $taxi) {
                    if ($taxi -> isAvailable = true){
                        //We note the necessary information:
                        $brand = $taxi -> Brand;
                        $licencePlate = $taxi -> License_plate;
                        $pricePerKm = $taxi -> Price_per_km;

                        echo "<tr class = 'taxiRow'>
                        <td>$brand</td>
                        <td>$licencePlate</td>
                        <td>$pricePerKm</td>
                        </tr>";
                    }
                }
            }
            ?>

            </tbody>
        </table>
    </div>
</div>

<br>
<br>
<br>


<!--Bottom bar with label and button -->
<div class="flex-container" id="bottomBar">
    <div class="flex-item">
        <label>
            Share mode is OFF
        </label>
    </div>

    <div class="flex-item">
        <button>
            Dispatch taxi

            <?php
            /**
             * TODO:
             * Take all request-rows with 'selected' class
             * Take the taxi row with 'selected' class
             * Post http-request to RESTApi with order information (time, payment, requestID, taxiID)
             *
             * NOTE: how do we get the ID's?
             */

            ?>

        </button>
    </div>


</div>


</body>

<footer>
    Developed by Rayan and Lise inc.
</footer>

</html>


