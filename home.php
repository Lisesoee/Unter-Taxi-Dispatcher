<?php
/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 16-05-2017
 * Time: 14:43
 *
 * Consider making a class inside this (e.g. called Homepage) and setting function availability
 */


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

function displayPendingRequests()
{
    $pendingRequestsTableRows = '';

    //Get the pending requests
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
                $pendingRequestsTableRows = $pendingRequestsTableRows . "<tr id = '$requestID' class = 'requestRow'>
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
    return $pendingRequestsTableRows;

}

function displayAvailableTaxis()
{
    $availableTaxiTableRows = '';

    //Get the available taxis:
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

                //We concatinate the new row with the others:
                $availableTaxiTableRows = $availableTaxiTableRows . "<tr id = '$taxiID' class = 'taxiRow'>
                        <td>$brand</td>
                        <td>$licencePlate</td>
                        <td>$pricePerKm</td>
                        </tr>";
            }
        }
    }
    return $availableTaxiTableRows;
}


?>
<!--<!DOCTYPE html>
<html>-->
<head>


    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


    <style>
        html {
            background-color: #ffff00;

        }

        header {
            background-color: #ffcc00;
            text-align: center;
            text-decoration-color: burlywood;
            padding: 2%
        }

        body {

        }

        footer {
            position: relative;
            right: 0;
            bottom: 0;
            left: 0;
            padding: 1rem;
            background-color: #ffff00;
            text-align: center;
        }

        dev {
            background-color: #ffff00;

        }

        h1 {
            font-size: large;
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
            overflow: auto;
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
            background-color: #ffcc00;
        }

        /*Makes sure that the table header doesn't light up on hover like the other table-rows*/
        th {
            background-color: #fff;
        }

        /*Selected row in both tables are coloured:*/
        .selectedRequest, .selectedTaxi {
            background-color: #ff9900;
        }

    </style>

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
        $(document).ready(function () {

            //When button is clicked:
            $("#dispatchButton").click(function () {

                //We set the taxiID
                $('.selectedTaxi').each(function () {
                    selectedTaxiID = this.id;

                    //For each selected request, we dispatch the taxi by posting an order to the database:
                    $('.selectedRequest').each(function () {
                        currentSelectedRequestID = this.id;

                        var currentRequest = {
                            "Estimated_Time": "5",
                            "Estimated_Payment": "some new payment",
                            "FK_Request_ID": currentSelectedRequestID,
                            "FK_Taxi_ID": selectedTaxiID
                        };

                        //We send a HTTP request to the RESTApi with the order information:
                        $.ajax({
                            //url: 'http://360itsolutions.dk/RESTApi.php/_Order',
                            url: 'http://localhost:8080/RESTApi.php/_Order',
                            type: "POST",
                            data: JSON.stringify(currentRequest),
                            processData: false,
                            success: function (data, textStatus, jqXHR) {
                                console.log(data);
                                alert("Order placed");

                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log("An error occurred: " + errorThrown);
                            }
                        });
                    })
                })
            });
        });
    </script>

    <script>
        /**
         * This function sorts the given column in the given HTML table
         *
         * #Credit to W3Schools from where the structure and logic for the search function comes from.
         * @param columnNumber: the number of the coloumn in the table (the first i 0, second is 1, etc)
         * @param currentTable: the table given that we want the column sorted in
         */
        function sortTable(columnNumber, currentTable) {
            var table, rows, switching, i, x, y, shouldSwitch, direction, switchcount = 0;
            table = document.getElementById(currentTable);
            switching = true;
            //Set the sorting direction to ascending:
            direction = "asc";

            //We create a loop that will continue as long as anything is switching. (If it runs and no switching has
            // been done, it will break.)
            while (switching) {
                //start by saying: no switching is done:
                switching = false;
                rows = table.getElementsByTagName("TR");

                //We loop through all the table rows (except the headers of cause (that's why i = 1)):
                for (i = 1; i < (rows.length - 1); i++) {
                    //We start by saying there should be no switching:
                    shouldSwitch = false;

                    //We get the two elements (the table data in the given column in the row we are checking) that we
                    // want to compare (current row and the next row):
                    x = rows[i].getElementsByTagName("td")[columnNumber];
                    y = rows[i + 1].getElementsByTagName("td")[columnNumber];

                    //Depending on the direction we check if the rows should be switched:
                    if (direction == "asc") {
                        //If we are ascending, we switch if the current element is larger than the second:
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            //We need to break the loop:
                            break;
                        }
                    } else if (direction == "desc") {
                        //If we are descending, we switch if the current is smaller than the next:
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            //And we break:
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    //Whenever the boolean is set true and the loop is broken, we want to actually do the switch and
                    // mark it to be done:
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    //Each time a switch is done, increase this count by 1:
                    switchcount++;
                } else {
                    //If no switching has been done AND the direction is "asc", we set the direction to "desc" and run
                    // the while loop again:
                    if (switchcount == 0 && direction == "asc") {
                        direction = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>

</head>
<!--
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
                <li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            </ul>
        </div>
    </nav>
</nav>
-->
<body>

<!--Tables: -->
<div class="flex-container" id="tableSection">
    <div>

        <h1>Pending requests</h1>
        <div class="flex-item" style="height: 420px;">


            <!--Table comments:
            We use thead-tag to make column header look special.
            When using this, all tr-tags needs to be in a tbody-tag.-->
            <table id="requestTable" class="table-condensed table-responsive">
                <thead>
                <tr>
                    <th onclick="sortTable(0, 'requestTable')">First name</th>
                    <th onclick="sortTable(1, 'requestTable')">Last name</th>
                    <th onclick="sortTable(2, 'requestTable')">Brand preff.</th>
                    <th onclick="sortTable(3, 'requestTable')">Phone no</th>
                    <th onclick="sortTable(4, 'requestTable')">From address</th>
                    <th onclick="sortTable(5, 'requestTable')">To address</th>
                    <th onclick="sortTable(6, 'requestTable')">Time</th>
                </tr>
                </thead>

                <tbody>
                <?php echo displayPendingRequests(); ?>
                </tbody>

            </table>
        </div>
    </div>

    <div>
        <h1>Available taxis</h1>
        <div class="flex-item" style="height: 420px;">

            <table id="availableTaxisTable" class="table-condensed table-responsive">
                <thead>
                <tr>
                    <th onclick="sortTable(0, 'availableTaxisTable'" )
                    ">Brand</th>
                    <th onclick="sortTable(1, 'availableTaxisTable')">Licence plate</th>
                    <th onclick="sortTable(2, 'availableTaxisTable')">Price per km</th>
                </tr>
                </thead>

                <tbody>
                <?php echo displayAvailableTaxis(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!--Bottom bar with label and button -->
<div class="flex-container" id="bottomBar" style="height: auto" frame="box">
    <div class="flex-item">

        <select>

            <?php /*
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

    </div>

    <div class="flex-item">
        <button id="dispatchButton">
            Dispatch taxi
        </button>
    </div>
</div>

</body>
<!--
<footer>
    Developed by Rayan and Lise inc.
</footer>

</html>
-->

