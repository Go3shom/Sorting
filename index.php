<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LAST</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <style>
        body { color: #010101; font-family: 'Roboto'; font-size: 20px; text-align: center; margin: 30px auto; }
        a { color: #69779b; text-decoration: none; text }
        a:hover { color: #010101; }
        table { border-collapse: collapse; width: 85%; text-align: center; margin: 30px auto; }
        th { padding: 10px; background-color: #69779b; }
        th > a { color: #F0ECE2; text-decoration: none; }
        th > a:hover { color: #010101; }
        tr:nth-child(even) { background-color: #F0ECE2; }
    </style>
</head>
<body>
    <?php
    $con = new mysqli( "localhost", "root", "", "targetdb" );
    
    if ( $con->connect_error ) {
        die( "Failed" . connection_error() );
    }
    
    // function sorting_query( $sort_field, $sort_order ) {
        if ( isset( $_GET[ 'sort_field' ] ) ) {
            $sort_field = $_GET[ 'sort_field' ];
            $sort_order = $_GET[ 'sort_order' ];

            $sort_order == 'DESC' ? $sort_order = 'ASC' : $sort_order = 'DESC';
        } else {
            $sort_order = 'ASC';
            $sort_field = 1;
        }
    // }
    
    echo "<h2><a href='/sorting'>Home</a></h2>";

    $sql = " SELECT ClientID, ClientName, ClientAddress FROM Clients ";

    $sql .= " ORDER BY $sort_field $sort_order";
    // $sql .= " ORDER BY $sort_order";
    
    $result = $con->query( $sql );

    echo "<table>";
        display_header( $result, $sort_order );
        display_rows( $result );
    echo "</table>";

    $con->close();
    /******************************************************************/
    function display_header( $result, $sort_order ) {
        $field_cnt = $result->field_count;
        for ( $i = 0; $i < $field_cnt; $i++ ) {
            $field= $result->fetch_field();
            $k = $i + 1;
            echo "<th><a href= '?sort_field=$k&&sort_order=$sort_order'>" . $field->name . "</a></th>";
            // echo "<th><a href= '?sort_field=$k'>" . $field->name . "</a></th>";
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
            echo "No Result Returned.";        
        }        
    }
    /******************************************************************/
    // if ( $result->num_rows > 0 ) {

        // $sort_order == 'DESC' ? $sort_order = 'ASC' : $sort_order = 'DESC';
    
    //     echo "
    //       <table>
    //         <thead>
    //             <tr>
    //                 <th><a href='?sort_field=clientid&&sort_order=$sort_order'>Client ID</a></th>
    //                 <th><a href='?sort_field=clientName&&sort_order=$sort_order'>Client Name</a></th>
    //                 <th><a href='?sort_field=clientAddress&&sort_order=$sort_order'>Client Address</a></th>
    //             </tr>
    //         </thead><tbody>";
        
    //     while ( $rows = $result->fetch_assoc() ) {
    //      echo "<tr><td>";
    //         echo $rows[ 'ClientID' ];
    //         echo "</td><td>";
    //         echo $rows[ 'ClientName' ];
    //         echo "</td><td>";
    //         echo $rows[ 'ClientAddress' ];
    //      echo "</td></tr>";
    //     }
    // } else {
    //     echo "No Result Returned.";
    // }
    // echo "</tbody></table>";

    // $con->close();

    ?>
</body>
</html>