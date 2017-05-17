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

    </style>

</head>
<body>

<div class="flex-container">
    <div class="flex-item">
        Customer Name
        <br>
        <div class="flex-item">
            john
        </div>
    </div>
    <div class="flex-item">
        Phone No.
        <br>
        <div class="flex-item">
            john number
        </div>
    </div>
    <div class="flex-item">
        Brand preference
        <br>
        <div class="flex-item">
            john number
        </div>
    </div>
    <div class="flex-item">
        Priority
        <br>
        <div class="flex-item">
            john number
        </div>
    </div>
    <div class="flex-item">
        From address
        <br>
        <div class="flex-item">
            john number
        </div>
    </div>
    <div class="flex-item">
        To address
        <br>
        <div class="flex-item">
            john number
        </div>
    </div>
</div>

<br> <br>

<table>
    <tr>
        <th>Customer Name</th>
        <th>Phone no</th>
        <th>Brand preference</th>
        <th>Priority</th>
        <th>From address</th>
        <th>To address</th>
    </tr>

    <?php

    echo "<tr>row
    </tr>"
    ?>
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
    <tr>
        <td>Jill Smith</td>
        <td>12345678</td>
        <td>Toyota</td>
        <td>10</td>
        <td>From this place</td>
        <td>To this place</td>
    </tr>
</table>
</body>
</html>


