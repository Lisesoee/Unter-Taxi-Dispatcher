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

        /*Adding some nice look and feel to the table rows: */
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

        .selected {
            background-color: #0066ff;
        }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        //This script adds functionality for selecting rows in the tables.
        //The 'selected' class is coloured differently using CSS and will be used when dispatching a taxi to the selected requests
        //TODO: restrict number of selections?
        $(document).ready(function () {
            $('table tr').click(function () {
                if ($(this).attr('class') == 'selected') {
                    $(this).removeClass('selected');
                } else {
                    $((this)).addClass('selected');
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
            //$Database = new Database();

            //$selectRequestsSql = "SELECT `FK_customer_ID`,`From_Location`, `To_Location` FROM `Request`";
            //$requests = $Database->doSelect($selectRequestsSql);

            //We get the json-file containing all the requests:
            $taxiRequests = file_get_contents('http://localhost:8080/RESTapi.php/Request');
            //We need to decode the http-response as an associative array so we can use and display it:
            $arr= json_decode($taxiRequests, true);

            //print_r($response);

            if (is_array($arr))
            {
                echo "is ARRAY!!!!!";

                foreach ($arr as $key => $value) {
                    echo "<tr><td>$key</td><td>$value</td></tr>";


                    //echo $value["From_Location"] . ", " . $value["To_Location"] . "<br>";
                }

                /**
            echo "<table>";
            foreach($arr as $k=>$v)
                echo "<tr><td>$k</td><td>$v</td></tr>";
            echo "</table>";


            foreach ( $arr as $key => $jsons) {
                $table ='<table class="'.$jsons['class'].'" border="1">';
                foreach ($jsons as $rkey => $rvalue) {
                    if($rkey=='head')
                    {
                        $table.='<tr>';
                        foreach($rvalue as $rvv)
                        {
                            $table.='<th>'.$rvv.'</th>';
                        }
                        $table.='</tr>';
                    }else
                        if($rkey=='rows')
                        {
                            foreach($rvalue as $rvv)
                            {
                                $table.='<tr>';
                                foreach($rvv as $rv)
                                {
                                    $table.='<td>'.$rv.'</td>';
                                }
                                $table.='</tr>';
                            }
                        }
                }
            }
            echo $table;
*/
            }





           /*
            if (is_array($response))
            {
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
                <th>Driver</th>
                <th>Brand</th>
                <th>Licence plate</th>
                <th>Price per km</th>

            </tr>
            </thead>

            <tbody>
            <tr>
                <td>Hans Hansen</td>
                <td>Suzuki</td>
                <td>123dgh23</td>
                <td>10 kr</td>
            </tr>
            <tr>
                <td>Hans Hansen</td>
                <td>Suzuki</td>
                <td>123dgh23</td>
                <td>10 kr</td>
            </tr>
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
        </button>
    </div>


</div>


</body>

<footer>
    Developed by Rayan and Lise inc.
</footer>

</html>


