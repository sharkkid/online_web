/*
        "sort_table.js"
        Zach Solcher
        all rights reserved.
 */

var data

    byTeam = 1,
    byWins = 1,
    byPts = 1;

window.onload = function () {
    data = document.getElementById("data");
}

function sort_table(tbody, col, asc) {
    var rows = tbody.rows,
        rlen = rows.length,
        arr = new Array(),
        i, j, cells, clen;
    // fill the array with values from the table
    for (i = 0; i < rlen; i++) {
        cells = rows[i].cells;
        clen = cells.length;
        arr[i] = new Array();
        for (j = 0; j < clen; j++) {
            arr[i][j] = cells[j].innerHTML;
        }
    }
    // sort the array by the specified column number (col) and order (asc)
    arr.sort(function (a, b) {
        return (a[col] == b[col]) ? 0 : ((a[col] > b[col]) ? asc : -1 * asc);
    });
    // replace existing rows with new rows created from the sorted array
    for (i = 0; i < rlen; i++) {
        rows[i].innerHTML = "<td>" + arr[i].join("</td><td>") + "</td>";
    }
}

function filter_table(tbody, filter) {

}