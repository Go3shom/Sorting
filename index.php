<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LAST</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        table { border-collapse: collapse; width: 85%; color: #010101; font-family: 'Roboto'; font-size: 20px; text-align: center; margin: 30px auto; }
        th {  padding: 10px; background-color: #69779b;}
        th > a { color: #F0ECE2; text-decoration: none; }
        th > a:hover { color: #010101; }
        tr:nth-child(even) { background-color: #F0ECE2; }
    </style>
</head>
<body>
    <?php
      $con = new mysqli( "localhost", "root", "", "targetdb" );
      if ( $con->connect_error ) {
          die( "Connection Error" . $con->connect_error );
      }
        
      // function sort_headers( $sort_field, $sort_order ) {
      $sort_field = 'ClientID';

      if ( isset( $_GET[ 'sort_field' ] ) ) {
        $sort_field = $_GET[ 'sort_field' ];
      }
      elseif ( $sort_field == [ 'ClientID' ] ) {
        $sort_field = 'ClientID';
      } 
      elseif( $sort_field == [ 'ClientName' ] ) {
        $sort_field = 'ClientName';
      } 
      elseif( $sort_field == [ 'ClientAddress' ] ) {
        $sort_field = 'ClientAddress';
      }
    // }
    
      if ( isset( $_GET[ 'sort_order' ] ) ) {
          $sort_order = $_GET[ 'sort_order' ];
      } else {
          $sort_order = 'ASC';
      }
      
      $sort_order == 'DESC' ? $sort_order = 'ASC' : $sort_order = 'DESC';


      $sql = "SELECT ClientID, ClientName, ClientAddress FROM Clients";
      // $sql = "SELECT ClientID FROM Clients";
      // $sql.= " ORDER BY $sort_field";
      $sql.= " ORDER BY $sort_field $sort_order";

      $result = $con->query( $sql );

        // sort_headers( $sort_field, $sort_order );
      
      echo "<table><thead><tr>";

        echo "<th><a href='?sort_field=$sort_field&&sort_order=$sort_order'>ClientID</a></th>";
        echo "<th><a href='?sort_field=$sort_field&&sort_order=$sort_order'>ClientName</a></th>";
        echo "<th><a href='?sort_field=$sort_field&&sort_order=$sort_order'>ClientAddress</a></th>";
    
      echo "</tr></thead>";

      if ( $result->num_rows > 0 ) {
          // $sort_order == 'DESC' ? $sort_order = 'ASC' : $sort_order = 'DESC';
          // var_dump($sort_order);
          
          while ( $row = $result->fetch_assoc() ) {
              echo "<td>";
              // $clientID = $row['ClientID'];
              echo $row['ClientID'];
              echo "</td><td>";
              // $clientName = $row['ClientName'];
              echo $row['ClientName'];
              echo "</td><td>";
              // $clientAddress = $row['ClientAddress'];
              echo $row['ClientAddress'];
              echo "</td></tr>";
          }
            echo "</tbody></table>";
        }
        
        $con->close();

    /******************************************************/

    ?>
</body>
</html>