<?php
/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 16-05-2017
 * Time: 14:43
 */

?>
<!DOCTYPE html>
<html>
<head>


    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



    <style>
        html {
            background-color: #ffff00

        }
        header {
            text-align: center;
            padding: 2%
        }

        footer {
            position: relative;
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
                if ($(this).hasClass('requestRow')) {
                    //If the request row is selected we unselect it:
                    if ($(this).hasClass('selectedRequest')) {
                        $(this).removeClass('selectedRequest');
                    }
                    //Else, we add select to it:
                    else {
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

    <script>
        /**
         * TODO:
         * Take all request-rows with 'selected' class
         * Take the taxi row with 'selected' class
         * Post http-request to RESTApi with order information (time, payment, requestID, taxiID)
         *
         * NOTE: how do we get the ID's?
         *
         * for each selected request
         *  --> post http with the given requestID and the taxiID if the chosen taxi
         */

        $(document).ready(function () {

            //When button is clicked:
            $("#dispatchButton").click(function () {

                //We set the taxiID
                $('.selectedTaxi').each(function(){
                    selectedTaxiID = this.id;

                    //For each selected request, we dispatch the taxi by posting an order to the database:
                    $('.selectedRequest').each(function(){
                        currentSelectedRequestID = this.id;

                        var currentRequest = {
                            "Estimated_Time": "5",
                            "Estimated_Payment": "some new payment",
                            "FK_Request_ID": currentSelectedRequestID,
                            "FK_Taxi_ID": selectedTaxiID
                        };

                        //TODO: set the URL to the webserver RESTApis url.
                        //TODO: But first, change the webservers code to have '_Order' instead of 'Order' and 'Estimated_Payment'
                        //TODO: insted of 'Estimated_payment' since this causes errors.
                        //TODO: -> E.G: TAKE THE MODIFIED VERSION THAT WORKS AND OVERWHRITE THE VERSION ON THE WEBSERVER!
                        //TODO: - This way we are sure that we got every single place, since I had to do a lot if debugging to find all the places where stuff was wrong.

                        //We send a HTTP request to the RESTApi with the order information:
                        $.ajax({
                            //url: 'http://360itsolutions.dk/RESTApi.php/_Order',
                            url: 'http://localhost:8080/RESTApi.php/_Order',
                            type: "POST",
                            data: JSON.stringify(currentRequest),
                            processData: false,
                            success: function (data, textStatus, jqXHR) {console.log(data);},
                            error: function (jqXHR, textStatus, errorThrown) {console.log("An error occurred: " + errorThrown);}
                        });

                    })
                })



            });
        });

    </script>

</head>

<header>
    <h1>Unter Dispatcher</h1>
    <img src="images/taxi.png" alt="taxi picture" style="width:150px;height:99px;"/>
</header>

<nav class="navbar navbar-default">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Unter Dispatcher</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Page 1</a></li>
                <li><a href="#">Page 2</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            </ul>
        </div>
    </nav>
</nav>

<body>

<!--Tables: -->
<div class="flex-container"  id="tableSection">
    <div class="flex-item">
        <!--Table comments:
        We use thead-tag to make column header look special.
        When using this, all tr-tags needs to be in a tbody-tag.-->
        <table id="requestTable" class = "table-condensed">
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
             * @param $params : the parameters for specific operations
             * @return bool|mixed|string: returns the decoded response from the RESTApi
             */
            function callRESTApi($params)
            {

                //We get the json-file containing all the requests:
                //$response = file_get_contents('http://360itsolutions.dk/RESTApi.php/'.$params);
                $response = file_get_contents('http://localhost:8080/RESTApi.php/' . $params);

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

            $taxiRequests = callRESTApi('request');
            if (is_array($taxiRequests)) {
                foreach ($taxiRequests as $request) {
                    //We note the necessary information:
                    $requestID = $request->ID;
                    $customerID = $request->FK_customer_ID;
                    $fromAddress = $request->From_Location;
                    $toLocation = $request->To_Location;
                    $time = $request->TimeStamp;

                    //We get the given customer and decode the response:
                    $customer = callRESTApi('_customer/' . $customerID);

                    //Even though its only one customer, we still loop the array:
                    foreach ($customer as $thisCustomer) {
                        $FName = $thisCustomer->FName;
                        $LName = $thisCustomer->LName;
                        $PreferredBrand = $thisCustomer->Preferred_Brand;
                        $PhoneNb = $thisCustomer->PhoneNb;

                        //For each request with the given customer, we echo to the table:
                        echo "<tr id = '$requestID' class = 'requestRow'>
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
            ?>
            </tbody>

        </table>
    </div>
    <div class="flex-item">
        <table id="availableTaxisTable" class = "table-condensed">
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
            $availableTaxis = callRESTApi('taxi');

            //In case of no available taxis we check:
            if (is_array($availableTaxis)) {
                //For each taxi we print it if it is available:
                foreach ($availableTaxis as $taxi) {
                    if ($taxi->isAvailable = true) {
                        //We note the necessary information:
                        $taxiID = $taxi->ID;
                        $brand = $taxi->Brand;
                        $licencePlate = $taxi->License_plate;
                        $pricePerKm = $taxi->Price_per_km;

                        echo "<tr id = '$taxiID' class = 'taxiRow'>
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

            <select>

                <?php/*
                //Get the modes as a json from the RESTApi
                $modes = callRESTApi("mode");
                //For each mode in the json-reply, print the name as a value in an option

                if (is_array($modes)) {
                    foreach ($modes as $mode) {
                        //We note the necessary information:
                        $modeName = $mode-> Name;

                        echo "<option value=\"$modeName\">$modeName</option>";


                    }
                }*/

                ?>


                <option value="volvo">Volvo</option>
                <option value="saab">Saab</option>
                <option value="opel">Opel</option>
                <option value="audi">Audi</option>
            </select>

        </label>
    </div>

    <div class="flex-item">
        <button id="dispatchButton">
            Dispatch taxi
        </button>
    </div>

</div>

</body>

<footer>
    Developed by Rayan and Lise inc.
</footer>

</html>


