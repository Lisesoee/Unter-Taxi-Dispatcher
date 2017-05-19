<?php
/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 16-05-2017
 * Time: 14:43
 */
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
        tr:nth-child(odd) { background-color : ##99ccff; }
        tr:nth-child(even) { background-color : ##b3daff; }
        tr:hover { background-color : #0066ff; }

        /*Makes sure that the table header doesn't light up on hover like the other table-rows*/
        th {
            background-color: #fff;}

        .selected {
            background-color: #0066ff;
        }

    </style>

    <script>
        $(document).ready(function() {

            $('table tr').click(function() {
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


<body>

<!--Tables: -->
<div class="flex-container" id = "tableSection">
    <div class="flex-item">
        <!--Table comments:
        We use thread-tag to make column header look special.
        When using this, all tr-tags needs to be in a tbody-tag.-->
        <table id = "requestTable">

            <thead>
            <tr>
                <th>Customer Name</th>
                <th>Phone no</th>
                <th>Brand preference</th>
                <th>Priority</th>
                <th>From address</th>
                <th>To address</th>
            </tr>
            </thead>


            <tbody>
            <tr>
                <td>Jill Smith</td>
                <td>12345678</td>
                <td>Toyota</td>
                <td>10</td>
                <td>From this place</td>
                <td>To this place</td>
            </tr>
            <tr>
                <td>Jill Smith</td>
                <td>12345678</td>
                <td>Toyota</td>
                <td>10</td>
                <td>From this place</td>
                <td>To this place</td>
            </tr>
            </tbody>

        </table>
    </div>
    <div class="flex-item">
        <table id = "availableTaxisTable">
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
<div class="flex-container" id = "bottomBar">
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


