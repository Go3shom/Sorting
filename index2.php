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
    $conn = new mysqli( "localhost", "root", "", "targetdb" );
    
    if ( $conn->connect_error ) {
        die( "Connection Failed" . connetion_error() );
    }

    if ( isset( $_GET[ 'order' ] ) ) {
        $order = $_GET[ 'order' ];
    } else {
        $order = 'ClientID';
    }

    if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];
    } else {
        $sort = 'ASC';
    }

    echo "<a href='/sorting'>Home</a>";

    $sql = "SELECT ClientID, ClientName, ClientAddress FROM Clients";

    $sql .= " ORDER BY $order $sort";

    $result = $conn->query( $sql );

    if ( $result->num_rows > 0 ) {

        $sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';

        echo "
        <table>
            <tr>
                <th><a href='?order=clientID&&sort=$sort'>Client ID</a></th>
                <th><a href='?order=clientName&&sort=$sort'>Client Name</a></th>
                <th><a href='?order=clientAddress&&sort=$sort'>Client Address</a></th>
            </tr>
        ";

        while ( $rows = $result->fetch_assoc() ) {
            $clientID = $rows[ 'ClientID' ];
            $clientName = $rows[ 'ClientName' ];
            $clientAddress = $rows[ 'ClientAddress' ];
            
            echo "
                <tr>
                    <td>$clientID</td>
                    <td>$clientName</td>
                    <td>$clientAddress</td>
                </tr>
            ";
        }
        echo '</table>';
    } else {
        echo "0 Results.";
    }
    ?>
</body>
</html>