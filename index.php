<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LAST</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <style>
        body { color: #010101; font-family: 'Roboto'; font-size: 20px; text-align: center; margin: 30px auto; }
        a { color: #010101; text-decoration: none; text }
        a:hover { color: #69779b; }
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
    
    if ( isset( $_GET[ 'sort_field' ] ) ) {
        $sort_field = $_GET[ 'sort_field' ];
    } else {
        $sort_field = 'clientID';
    }
    
    if ( isset( $_GET[ 'sort_order' ] ) ) {
        $sort_order = $_GET[ 'sort_order' ];
    } else {
        $sort_order = 'ASC';
    }

    echo "<a href='/sorting'>Home</a>";

    $sql = " SELECT ClientID, ClientName, ClientAddress FROM Clients ";

    $sql .= " ORDER BY $sort_field $sort_order";
    
    $result = $con->query( $sql );
    
    
    if ( $result->num_rows > 0 ) {

        $sort_order == 'DESC' ? $sort_order = 'ASC' : $sort_order = 'DESC';
    
        echo "
          <table>
            <thead>
                <tr>
                    <th><a href='?sort_field=clientid&&sort_order=$sort_order'>Client ID</a></th>
                    <th><a href='?sort_field=clientName&&sort_order=$sort_order'>Client Name</a></th>
                    <th><a href='?sort_field=clientAddress&&sort_order=$sort_order'>Client Address</a></th>
                </tr>
            </thead><tbody>";
        
        while ( $rows = $result->fetch_assoc() ) {
         echo "<tr><td>";
            echo $rows[ 'ClientID' ];
            echo "</td><td>";
            echo $rows[ 'ClientName' ];
            echo "</td><td>";
            echo $rows[ 'ClientAddress' ];
         echo "</td></tr>";
        }
    } else {
        echo "No Result Returned.";
    }
    echo "</tbody></table>";

    $con->close();

    ?>
</body>
</html>