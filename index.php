<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sorting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <style>
      /* Color Palette: #010101 | #69779b | #ACDBDF | #F0ECE2 */
        body { color: #010101; font-family: 'Roboto'; font-size: 20px; text-align: center; margin: 30px auto; width: 100%; }
        .container { width: inherit; }
        .main { color: #69779b; }
        a { color: #69779b; text-decoration: none; text }
        a:hover { color: #010101; }
        hr { border: 1px solid #69779b; width: 90%; }
        /* Table */
        table { border-collapse: collapse; width: 85%; text-align: center; margin: 30px auto; }
        th { padding: 10px; background-color: #69779b; }
        th > a { color: #F0ECE2; text-decoration: none; }
        th > a:hover { color: #010101; }
        tr:nth-child(even) { background-color: #F0ECE2; }
        /* Pagination */
        .pagination-container { display: inline-flex; justify-content: center; width: 17%; font-size: 1.2rem; border: 5px solid #69779b; border-radius: 15px; }
        .pagination { margin: -10px 0; padding: 5px; }
        .pagination-item { border-radius: 15px; text-decoration: none; color: #010101; margin: -2px; padding: 5px; border: 1.4px solid #69779b; background-color: #F0ECE2; }
        .pagination-item:hover, .pagination-item:active { background-color: #69779b; color: #ACDBDF; }
        .disabled { background-color: #F0ECE2; pointer-events: none; cursor: not-allowed; opacity: 0.5; }
    </style>
</head>
<body>
    <?php
    $con = new mysqli( "localhost", "root", "", "targetdb" );
    
    if ( $con->connect_error ) {
        die( "Failed" . connection_error() );
    }
    // Variables
    $page_records = 10;    // No Of Recordes Retrieved Per Page. 

    // Retreive page number
    if ( isset( $_GET["page"] ) ) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }
    // Retreive records per page
    if ( $page == '' || $page == 1 ) {
        $offset = 0;
    } else {
        $offset = ( $page * $page_records ) - $page_records;
    }

    // Sorting records according to certain column name.
    if ( isset( $_GET[ 'sort_field' ] ) ) {
        $sort_field = $_GET[ 'sort_field' ];
        $sort_order = $_GET[ 'sort_order' ];

        // Check for column sort order & toggle it.
        $sort_order == 'DESC' ? $sort_order = 'ASC' : $sort_order = 'DESC';

    } else {
        $sort_order = 'ASC';
        $sort_field = 1;
    }
    

    echo "<div class='container'>";

        echo "<h2><a href='/sorting'>Home</a></h2>";
        echo "<hr>";
        

        $sql = "SELECT ClientID, ClientName, ClientAddress FROM Clients";    
        $sql .= " ORDER BY $sort_field $sort_order";
        $sql .= " LIMIT $offset, $page_records";

        $result = $con->query( $sql );


        // Pagination Links
        $pag_sql = "SELECT ClientID, ClientName, ClientAddress FROM Clients";
        $data = $con->query( $pag_sql );
        $rescords = $data->num_rows;
        $page_records = ceil( $rescords / $page_records );
        $prev = $page - 1;
        $next = $page + 1;

        // echo "<pre>";
        // var_dump($offset);
        // echo "</pre>";
        // var_dump($sql);
        // echo "<pre>";
        // echo($offset);
        // echo "</pre>";

        pagination( $page_records, $prev, $next );

        echo "<table>";
            display_header( $result, $sort_order, $page );
            display_rows( $result );
        echo "</table>";        

        echo "<hr>";

    echo "</div>";

    $con->close();

    /****************************************************************/
    /*------------------------ FUNCTIONS ---------------------------*/
    /****************************************************************/
    
    function pagination( $page_records, $prev, $next ) {
        echo"<div class='pagination-container'>";
            echo"<div class='pagination'>";
        
            if ( $page_records >= 0 ) {
                
                echo '<a class="pagination-item disabled" href="?page=' . $prev .'">Prev</a>';

                for ( $i = 1; $i <= $page_records; $i++ ) { 
                    // if ( $i < 1 ) {
                        // echo '<a class="pagination-item disabled" href="?page=' . $prev .'">Prev</a>';
                    // } elseif ( $i >= $page_records ) {
                        // echo '<a class="pagination-item disabled" href="?page=' . $next .'">Next</a>';
                    // } else {
                        echo "<a class='pagination-item' href='?page=$i'>" . $i . "</a>";
                    }
                }
                echo '<a class="pagination-item disabled" href="?page=' . $next .'">Next</a>';

            // }
                
            echo "</div>
        </div>";
    }
    /******************************************************************/
    function display_header( $result, $sort_order, $page ) {
        $field_cnt = $result->field_count;

        for ( $i = 0; $i < $field_cnt; $i++ ) {
            $field= $result->fetch_field();
            $k = $i + 1;
            
            // Check for column sort order
            // $sort_order == 'DESC' ? $sort_order = 'ASC' : $sort_order = 'DESC';

            // echo "<pre>";
            // var_dump($sort_order);
            // echo "</pre>";

            echo "<th><a href='?page=$page&sort_field=$k&sort_order=$sort_order'>" . $field->name . "</a></th>";
        }
    }
    /******************************************************************/
    function display_rows( $result ) {
        $field_cnt = $result->field_count;
        if ( $result->num_rows > 0) {
            while ( $row = $result->fetch_array() ) {
                echo "<tr>";
                    for ( $j = 0; $j < $field_cnt; $j++) { 
                        echo "<td>" . $row[ $j ] . "</td>";
                    }
                echo "</tr>";
            }
        } else {
            echo "<h4 class='main'>No Result Returned.</h4>";
        }
    }
    /******************************************************************/    
    ?>
</body>
</html>