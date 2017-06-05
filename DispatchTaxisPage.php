<?php

/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 05-06-2017
 * Time: 18:53
 */

require("HomePage.php");

class DispatchTaxisPage extends HomePage
{
    /**
     * This function calls the RESTApi to place a HTTP request and do a CRUD function in the database
     *
     * @param $params : the parameters for specific operations
     * @return bool|mixed|string: returns the decoded response from the RESTApi
     */
    public function callRESTApi($params)
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

    public function displayPendingRequests()
    {
        $pendingRequestsTableRows = '';

        //Get the pending requests
        $taxiRequests = $this->callRESTApi('request');
        if (is_array($taxiRequests)) {
            foreach ($taxiRequests as $request) {
                //We note the necessary information:
                $requestID = $request->ID;
                $customerID = $request->FK_customer_ID;
                $fromAddress = $request->From_Location;
                $toLocation = $request->To_Location;
                $time = $request->TimeStamp;

                //We get the given customer and decode the response:
                $customer = $this->callRESTApi('_customer/' . $customerID);

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

    public function displayAvailableTaxis()
    {
        $availableTaxiTableRows = '';

        //Get the available taxis:
        $availableTaxis = $this->callRESTApi('taxi');

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
}

/**
 * Following code is for creating the page, setting the different parts of the html document, and displaying the page.
 */
//We initialize the page:
$dispatchPage = new DispatchTaxisPage();
//we add page-specific links:
$dispatchPage->additionalLinks = "
<script src=\"js/dispatchTaxisPageFunctions.js\" type=\"text/javascript\"></script>
<script src=\"js/sortTableGeneric.js\" type=\"text/javascript\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/customStyles.css\">;

";

//We set the body/content of the page:
//TODO: split the body into sections and concatenate to the pageContent. This is way to messy!
$dispatchPage->pageContent = "<body>
<!--Tables: -->
<div class=\"flex-container\" id=\"tableSection\">
    <div>

        <h1>Pending requests</h1>
        <div class=\"flex-item\" style=\"height: 420px;\">


            <!--Table comments:
            We use thead-tag to make column header look special.
            When using this, all tr-tags needs to be in a tbody-tag.-->
            <table id=\"requestTable\" class=\"table-condensed table-responsive\">
                <thead>
                <tr>
                    <th onclick=\"sortTable(0, 'requestTable')\">First name</th>
                    <th onclick=\"sortTable(1, 'requestTable')\">Last name</th>
                    <th onclick=\"sortTable(2, 'requestTable')\">Brand preff.</th>
                    <th onclick=\"sortTable(3, 'requestTable')\">Phone no</th>
                    <th onclick=\"sortTable(4, 'requestTable')\">From address</th>
                    <th onclick=\"sortTable(5, 'requestTable')\">To address</th>
                    <th onclick=\"sortTable(6, 'requestTable')\">Time</th>
                </tr>
                </thead>

                <tbody>" . $dispatchPage->displayPendingRequests() . "</tbody>

            </table>
        </div>
    </div>

    <div>
        <h1>Available taxis</h1>
        <div class=\"flex-item\" style=\"height: 420px;\">

            <table id=\"availableTaxisTable\" class=\"table-condensed table-responsive\">
                <thead>
                <tr>
                    <th onclick=\"sortTable(0, 'availableTaxisTable'\" )\">Brand</th>
                    <th onclick=\"sortTable(1, 'availableTaxisTable')\">Licence plate</th>
                    <th onclick=\"sortTable(2, 'availableTaxisTable')\">Price per km</th>
                </tr>
                </thead>

                <tbody>" . $dispatchPage->displayAvailableTaxis() . "
                </tbody>
            </table>
        </div>
    </div>
</div>


<!--Bottom bar with label and button -->
<div class=\"flex-container\" id=\"bottomBar\" style=\"height: auto\" frame=\"box\">
    <div class=\"flex-item\">

        <select>
            <option value=\"volvo\">Volvo</option>
            <option value=\"saab\">Saab</option>
            <option value=\"opel\">Opel</option>
            <option value=\"audi\">Audi</option>
        </select>

    </div>

    <div class=\"flex-item\">
        <button id=\"dispatchButton\">
            Dispatch taxi
        </button>
    </div>
</div>

</body>";

//And finally, we display the page (after all the specific sections has been set)
$dispatchPage->DisplayPage();