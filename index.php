<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sorting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <style>
      /* Color Palette: #010101 | #69779B | #ACDBDF | #F0ECE2 */
      /* Color Palette: #cdffeb | #009f9d | #07456f | #0f0a3c */
      /* Color Palette: #f4f9f4 | #a7d7c5 | #74b49b | #5c8d89 */
      /* Color Palette: #3a0088 | #930077 | #e61c5d | #ffe98a */
      /* Color Palette: #900c3f | #c70039 | #ff5733 | #ffc300ح */
      :root { --primary: #69779B; --secondary: #ACDBDF; --shadow: #010101; --link: #F0ECE2; }

      body { color: var(--shadow); font-family: 'Lobster', 'Roboto'; font-size: 20px; text-align: center; margin: 30px auto; width: 100%; }
      .container { width: inherit; }
      .main { color: var(--primary); }
      a { color: var(--primary); text-decoration: none; text }
      a:hover { color: var(--shadow); }
      hr { border: 1px solid var(--primary); width: 90%; }
      /* Table */
      table { border-collapse: collapse; width: 85%; text-align: center; margin: 30px auto; }
      th { padding: 10px; background-color: var(--primary); }
      th > a { color: var(--link); text-decoration: none; }
      th > a:hover { color: var(--shadow); }
      tr:nth-child(even) { background-color: var(--link); }
      /* tr:nth-child(odd) { background-color: var(--secondary); } */
      /* td { border-radius: 20rem; } */
      /* Pagination */
      .pagination-container { display: inline-flex; font-size: 1.3rem; border-radius: 15px; }
      .pagination { margin: -2px 0; padding: 5px 0; }
      .pagination-item { border-radius: 20px; text-decoration: none; color: var(--shadow); margin: -1.5px; padding: 5px; border: 1.4px solid var(--primary); background-color: var(--link); }
      .pagination-item:hover, .active { background-color: var(--primary); color: var(--secondary); text-decoration: underline; }
      .active { border: 4px double var(--secondary); pointer-events: none; }
      .disabled { background-color: var(--primary); color: var(--secondary); pointer-events: none; opacity: 0.5; }
      .hidden { visibility: hidden; display: none; }
      /* Input Fields */
      select { cursor: pointer; font-size: 1.1rem; background-color: var(--link); color: var(--primary); padding: 0.5rem; border-radius: 7px; margin: 0.8rem; }
      button { line-height: 1.3rem; font-size: 1.1rem; color: var(--shadow); padding: 0.5rem; border-radius: 7px; background-color: var(--link); }
      button:hover { color: var(--secondary); text-decoration: underline; padding: 0.5rem; border-radius: 7px; background-color: var(--primary); }
    </style>
</head>
<body>

<!-- Starting -->
<?php
    // Creating a new connection.
    $connection = new mysqli( 'localhost', 'root', '', 'targetdb' );
    
    // Checking connection availability.
    if ( $connection->connect_error ) {
        die( '<b>Failed to establish connection</b>' . connection_error() );
    }

    /****************************************************************/
    /*------------------------ VARIABLES ---------------------------*/
    /****************************************************************/
    $items_per_page = 10;

    /****************************************************************/
    /*---------------------- GET VARIABLES -------------------------*/
    /****************************************************************/

    // Retreiving page number "if any".
    if ( isset( $_GET[ 'page_num' ] ) ) {
        $page_num = $_GET[ 'page_num' ];
           if ( $page_num <= 0 ) {
            $page_num = 1;
        }
        // elseif ( $page_num > $items_per_page ) {
        //     $page_num = $items_per_page;
        // } else {
        //     $page_num = $_GET[ 'page_num' ];
        // }
    } else {
        $page_num = 1;
    }
    
    // Retreiving number of records per page.
    if ( $page_num == '' || $page_num == 1 ) {
        $offset = 0;
    } else {
        $offset = ( $page_num * $items_per_page ) - $items_per_page;
    }

    // Sorting records according to certain field name.
    if ( isset( $_GET[ 'sort_field' ] ) ) {
        $sort_field = $_GET[ 'sort_field' ];
    } else {
        $sort_field = 1;
    }

    // Sorting records according to certain field order.
    if ( isset( $_GET[ 'sort_order' ] ) ) {
        $sort_order = $_GET[ 'sort_order' ];
    } else {
        $sort_order = 'ASC';
    }


/**************************************************************************************************/ 
    
    echo '<div class="container">';

        echo '<h2><a href="./">Sorting</a></h2>';
        echo '<hr>';

        $sql = "SELECT ClientID, ClientName, ClientAddress FROM Clients";
        $sql .= " ORDER BY $sort_field $sort_order";
        $sql .= " LIMIT $offset, $items_per_page";

        // sql_constructor( $sql, $sort_field, $sort_order, $offset, $items_per_page );

        $result = $connection->query( $sql );


        // Pagination Links
        
        /**
         * 
         * I tried to make this pagination with the same variables "$sql" & "$result" 
         * but all the attempted trials got failed.
         * I just do it as the same way I did before, & I handeled all its scenario cases.
         * 
         **/ 
        
        $page_sql = "SELECT ClientID, ClientName, ClientAddress FROM Clients";
        $data = $connection->query( $page_sql );
        $items_num = $data->num_rows;
        $items_per_page = ceil( $items_num / $items_per_page );


        pagination( $page_num, $items_per_page, $sort_field, $sort_order );


        echo '<table>';
            display_header( $result, $sort_order, $page_num );
            display_rows( $result );
        echo '</table>';

        echo '<hr>';

    echo '</div>';

    $connection->close();
    /****************************************************************/
    /*------------------------ FUNCTIONS ---------------------------*/
    /****************************************************************/    

    // function sql_constructor( $sql, $sort_field, $sort_order, $offset, $items_per_page ) {
    //     $sql .= " ORDER BY $sort_field $sort_order";
    //     $sql .= " LIMIT $offset, $items_per_page";
    //     var_dump($sql);
    //     echo '<br/><br/>';
    //     return $sql;
    // }

    /*********************************************************************/
    //       Looping on items to make the equivelent page numbers.       //
    /*********************************************************************/
    function pagination(  $page_num, $items_per_page, $sort_field, $sort_order ) {
        $prev = $page_num - 1;
        $next = $page_num + 1;

        // if ( $page_num <= 0 || $page_num > $items_per_page ) {
        //     echo '<h4 class="main">Wrong Data.!</h4>';
        // } else {
            echo '<div class="pagination-container">';
                echo '<div class="pagination">';
                
            // Toggle Prev button according to number of pages, & checking if it has sort_field & sort_order.
            if ( $page_num == '' || $page_num == 1 ) {
                echo '<a class="pagination-item hidden" ' . $prev . '">Prev</a>';
            }
            else {
                if ( $sort_field == 1 && $sort_order == 'ASC' ) {
                    echo '<a class="pagination-item" href="?page_num=' . $prev . '">Prev<a>';
                } else {
                    echo '<a class="pagination-item" href="?page_num=' . $prev . '&sort_field=' . $sort_field . '&sort_order=' . $sort_order . '">Prev</a>';
                }                    
            }

            for ( $i = 0; $i < $items_per_page; $i++ ) { 
                $j = $i + 1;
                // Adding active class to the current page number.
                if ( $j == $page_num ) {
                    echo '<a class="pagination-item active" href="?page_num=' . $j . '">'.$j.'</a>';
                } else {
                    echo '<a class="pagination-item" href="?page_num=' . $j . '">' . $j . '</a>';
                }
            }

            // Toggle Next button according to number of pages, & checking if it has sort_field & sort_order.
            if ( $page_num >= $items_per_page ) {
                echo '<a class="pagination-item hidden"' . $next . ' ">Next</a>';
            } else {
                if ( $sort_field == 1 && $sort_order == 'ASC' ) {
                    echo '<a class="pagination-item" href="?page_num=' . $next . '">Next</a>';
                } else {
                    echo '<a class="pagination-item" href="?page_num=' . $next . '&sort_field=' . $sort_field . '&sort_order=' . $sort_order . '">Next</a>';
                }
            }
        // }
        echo '</div>
    </div>';
    }
    
    /*********************************************************************/
    // Looping on table header rows, drawing & displaying header names.  //
    /*********************************************************************/
    
    // function display_header( $sql, $result, $sort_order, $offset, $items_per_page ) { 

    function display_header( $result, $sort_order, $page_num ) {
        $field_cnt = $result->field_count;

        // Check & Toggling sort order between ASC & DESC.
        $sort_order == 'DESC' ? $sort_order = 'ASC' : $sort_order = 'DESC';

        for ( $i = 0; $i < $field_cnt; $i++ ) { 
            $field = $result->fetch_field();
            // incrementing counter by 1 to start with "1" instead of "0".
            $k = $i + 1;

            // Printing Out the column names.
            echo "<th><a href='?page_num=$page_num&sort_field=$k&sort_order=$sort_order '>" . $field->name . "</a></th>";
        }
        // sql_constructor( $sql, $k, $sort_order, $offset, $items_per_page );
    }

    /********************************************************************/
    //    Looping on table data, drawing rows & displaying its data.    //
    /*********************************************************************/

    function display_rows( $result ) {
        $field_cnt = $result->field_count;

        // Checking if table has rows or not, if it has; draw the table rows, table data tags & fetch data in, else return a message.
        if ( $result->num_rows > 0 ) {
            while ( $rows = $result->fetch_array() ) {
                echo "<tr>";
                    for ( $j = 0; $j < $field_cnt; $j++ ) { 
                        echo "<td>" . $rows[ $j ] ."</td>";
                    }
                echo "</tr>";
            }
        } else {
            echo "<h4 class='main'>No Result Returned.</h4>";
        }
    }

?>