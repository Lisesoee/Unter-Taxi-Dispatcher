<?php

/**
 * Created by PhpStorm.
 * User: LiseMusen
 * Date: 05-06-2017
 * Time: 18:30
 */

/**
 * Class HomePage is a generic class for pages in the dispatcher program.
 * It builds up the page and let subclasses add content and links using public variables.
 * The subclasses can, after setting and adding the appropriate content, call the DisplayPage-method,
 * and the html will be displayed.
 */
class HomePage
{
    public $pageContent;
    public $additionalLinks;
    public $programName = "Unter Dispatcher";
    public $footerText = "Made by Rayan and Lise inc.";


    /**
     * This function get the content of the RESTApi.php file
     * This works very similar to a GET-request, its just simpler since we do not need cURL
     *
     * @param $params : the parameters for specific operations
     * @return bool|mixed|string: returns the decoded response from the RESTApi
     */
    public function callRESTApi($params)
    {

        //We get the json-file containing all the requests:
        $response = file_get_contents('http://87.54.141.140/TechnicalServices/RESTApi.php/' . $params);

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


    /**
     * This function displays everything for the page (except the specific non-generic content)
     * Needs to be called from subclass to show the proper information in the html file
     */
    public function DisplayPage()
    {
        echo "<!DOCTYPE html><html>";

        $this->DisplayNavigationBar();
        $this->DisplayHead();

        echo $this->pageContent;

        $this->DisplayFooter();

        echo "</html>";

    }

    /**
     * This function gets the html for the head of any generic HomePage.
     * Using the 'additionalLinks' variable, the subpages can add page-specific links.
     */
    public function DisplayHead()
    {

        echo "<head>
                <meta charset=\"utf-8\"/>
                <!-- Latest compiled and minified CSS -->
                <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">
                <!-- jQuery library -->
                <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script>
                <!-- Latest compiled JavaScript -->
                <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
                
                <script>
                $(document).ready(function () {
                    $('ul.nav > li').click(function (e) {
                        e.preventDefault();
                        $('ul.nav > li').removeClass('active');
                        $(this).addClass('active');                
                    });            
                });
                </script>"

            . $this->additionalLinks .

            "</head>";
    }

    /**
     * This function displays the generic navigation bar for the page
     * TODO: continue working on this; the pages should be the right ones and the navigation should work
     */
    private function DisplayNavigationBar()
    {
        ?>
        <nav class="navbar navbar-default">
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                            <a class="navbar-brand" href="DispatchTaxisPage.php">
                                <img class="img-responsive2" src="images/unterLogo.png">
                            </a>
                        <a class="navbar-brand" href="#">Unter Dispatcher</a>

                    </div>
                    <ul class="nav navbar-nav">
                        <li><a href="DispatchTaxisPage.php">Dispatch Taxis</a></li>
                        <li><a href="ManageCustomers.php">Decrease customer priority</a></li>
                        <li><a href="ManageCustomers.php">Manage Customers</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">

                        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    </ul>
                </div>
            </nav>
        </nav>
        <?php
    }

    /**
     * This function displays the generic footer for all DispatcherPages
     */
    private function DisplayFooter()
    {
        echo "<footer>
                <img src=\"images/unterLogo.png\" alt=\"UnterLogo\" class=\"image-responsive\" height=\"60\" width=\"60\">

                Developed by Rayan and Lise inc.
            </footer>";
    }
}

