/**
 * Created by LiseMusen on 05-06-2017.
 */
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