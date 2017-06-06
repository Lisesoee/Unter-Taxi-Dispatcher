/**
 * Created by LiseMusen on 05-06-2017.
 */
//This script adds functionality for selecting rows in the tables.
//The 'selected...' classes are coloured differently using CSS and will be used when dispatching the selected taxi to the selected requests

$(document).ready(function () {

    //alert('loaded'); //for debugging purposes

    //$(document).on('click','table tr', function(){
    $('table tr').click(function () {

        //alert('click fired'); //for debuggin purposes

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
                    url: 'http://localhost:8080/RESTApi.php/_order',
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


