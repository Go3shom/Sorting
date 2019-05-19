<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <link rel="stylesheet" type="text/css" href="./main.css"/>
    <title>Training</title>
</head>
<body>
  <?php
  /****************************************************************/
  /*---------------------- GET VARIABLES -------------------------*/
  /****************************************************************/
    session_start();

    // Sorting Records According To Certain Field Name.
    $sortField = $_GET[ 'sortField' ] ?? 1;

    // Sorting Records According To Certain Field Order.
    $sortOrder = $_GET[ 'sortOrder' ] ?? 'ASC';

    // Retrive Number Of Records Per Page.
    $recordsPerPage = $_GET[ 'recordsPerPage' ] ?? 20;

    // Choose A Selected Number Of Rows.
    $selectedItems = $_GET[ 'selectedItems' ] ?? $recordsPerPage;

    // Retrive Page Numbers.
    if ( isset( $_GET[ 'page' ] ) ) {
      $page = $_GET[ 'page' ];

      if ( $page < 1 ) {
        $page = 1;
      }
    } else {
      $page = 1;
    }

    // Search For Specific String Character.
    if ( isset( $_GET[ 'searchQuery' ] ) ) {
      $searchQuery = $_GET[ 'searchQuery' ];
      $_SESSION[ 'searchQuery' ] = $searchQuery;
    } else {
      $searchQuery = '';
    }

/****************************************************************/
/****************************************************************/
/****************************************************************/

    $sql = initialSql();

    // Connect To Database.
    $connection = connectionString();

    // Fetch Initial Result Set To Make A Selection Option.
    $initialResult = $connection->query( $sql );

    $numRows = $initialResult->num_rows;

    // Call SQL Query After Concatination.
    $constructedSql = constructSqlQuery( $page, $recordsPerPage, $sortField, $sortOrder, $searchQuery );

    $result = $connection->query( $constructedSql );

  ?>


  <!-- START MAIN CONTAINER -->
  <div class="container">

    <h1><a href="index.php">Training</a></h1>
    <hr>

    <!-- START PAGINATION CONTAINER -->
    <div class="pagination-container">
      <div class="pagination">
        <?php pagination( $connection, $sql, $result, $page, $recordsPerPage, $numRows, $searchQuery ); ?>
      </div>
    </div>
    <!-- END PAGINATION CONTAINER -->

    <!-- START FILTER CONTAINER -->
    <div class="filter-container">

      <!-- START FORM -->
      <form action="<?php $_SERVER[ 'PHP_SELF' ] ?>" method="GET">

        <!-- START SELECT OPTIONS -->
        <label for="recordsPerPage">Items No:</label>

        <select name="recordsPerPage">
          <?php for( $i = 20; $i < $numRows; $i += 20 ) : ?>

            <?php if( $i > 80 ) break; ?>

            <?php if ( $i == $selectedItems ) : ?>

              <option value="<?php echo $i; ?>" selected="selected"><?php echo $i; ?></option>

            <?php else : ?>
              <option value="<?php echo $i; ?>"><?php echo $i; ?></option>

            <?php endif; ?>

          <?php endfor; ?>
        </select>
        <!-- END SELECT OPTIONS -->

        <input type="text" name="searchQuery" placeholder="Search for a Client">
        <button type="submit" name="submit" value="Filter">Search</button>


      </form>
      <!-- END FORM -->

    </div>
    <!-- END FILTER CONTAINER -->


    <table>
      <thead>
        <?php displayQueryHeader( $result, $page, $recordsPerPage, $sortOrder, $searchQuery ); ?>
      </thead>
      <tbody>
        <?php displayQueryRows( $result ); ?>
      </tbody>
    </table>

    <?php session_destroy(); ?>
    <?php $connection->close(); ?>

    <hr>
  </div>
  <!-- END MAIN CONTAINER -->
</body>
</html>

  <?php

	/****************************************************************/
	/*------------------------ FUNCTIONS ---------------------------*/
	/****************************************************************/

      /**********************************************************************/
      //                Establish New Connection To Database.               //
      /**********************************************************************/
      function connectionString() {

        $connection = new mysqli( 'localhost', 'root', '', 'targetdb' );

        if ( $connection->connect_error ) {
          die( '<b>Failed to establish connection</b>' . connection_error() );
        }

        return ( $connection );
      }

      /**********************************************************************/
      //          Initialize The Main Sql Query To Fetch All Data.          //
      /**********************************************************************/
      function initialSql() {

        $sql = "SELECT id AS 'ID',
                       client_id AS 'Client ID',
                       client_name AS 'Client Name',
                       client_address AS 'Client Address'
                FROM `client`";

        return ( $sql );
      }

      /**********************************************************************/
      //  Construct SQL Query Sorting Fields, Orders & Search Query Result. //
      /**********************************************************************/
      function constructSqlQuery( $page, $recordsPerPage, $sortField, $sortOrder, $searchQuery ) {

        $constructedSql = initialSql();

        $limit = ' LIMIT ' . ( $page - 1 ) * $recordsPerPage . ', ' .$recordsPerPage;

        if ( $searchQuery <> '' ) {
          $constructedSql .= " WHERE CONCAT( id, client_id, client_name, client_address ) LIKE '%". $searchQuery ."%'";
        }

        if ( $sortField <> '1' || $sortOrder <> 'ASC' ) {
          $constructedSql .= " ORDER BY $sortField $sortOrder";
        }

        $constructedSql .= $limit;

        return ( $constructedSql );
      }

      /**********************************************************************/
      //  Looping On Table Header Rows, Drawing & Displaying Header Names.  //
      /**********************************************************************/
      function pagination( $connection, $sql, $result, $page, $recordsPerPage, $numRows, $searchQuery ) {

        $lastPage = ceil( $numRows / $recordsPerPage );
        $firstPage = ceil( $recordsPerPage / $numRows );

        if ( $page >= $lastPage ) {
          $page = $lastPage;
        }

        // Center The Active Page.
        $centerPage = '';
        $sub1 = $page - 1;
        $sub2 = $page - 2;
        $add1 = $page + 1;
        $add2 = $page + 2;


        if ( $page == 1) {
          $centerPage .= '<span class="pagination-item active">'.$page.'</span>';
          $centerPage .= '<a class="pagination-item" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'&searchQuery='.$searchQuery.'">'.$add1.'</a>';

        } elseif ( $page == $lastPage ) {
          $centerPage .= '<a class="pagination-item" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'&searchQuery='.$searchQuery.'">'.$sub1.'</a>';
          $centerPage .= '<span class="pagination-item active">'.$page.'</span>';

        } elseif ( $page > 2 && $page < ( $lastPage - 1 ) ) {
          $centerPage .= '<a class="pagination-item" href="'.$_SERVER['PHP_SELF'].'?page='.$sub2.'">'.$sub2.'</a>';
          $centerPage .= '<a class="pagination-item" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">'.$sub1.'</a>';
          $centerPage .= '<span class="pagination-item active">'.$page.'</span>';
          $centerPage .= '<a class="pagination-item" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">'.$add1.'</a>';
          $centerPage .= '<a class="pagination-item" href="'.$_SERVER['PHP_SELF'].'?page='.$add2.'">'.$add2.'</a>';

        } elseif ( $page > 1 && $page < $lastPage ) {
          $centerPage .= '<a class="pagination-item" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">'.$sub1.'</a>';
          $centerPage .= '<span class="pagination-item active">'.$page.'</span>';
          $centerPage .= '<a class="pagination-item" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">'.$add1.'</a>';
        }

        // Display Paagination Buttons.
        $paginationDisplay = '';

        if ( $lastPage != "1" ) {
          $paginationDisplay .= '<h3 style="background-color:var(--primary);border-radius:40%;border:0.4rem double var(--secondary);color:var(--link);min-width:195px;">Page <span style="color:var(--secondary);">'.$page.'</span> of <span style="color:var(--secondary);">'.$lastPage.'</span></h3>';

          if ( $page != 1 ) {
            $prev = $page - 1;
            $paginationDisplay .= '<a class="pagination-item" href="?page='.$firstPage.'&searchQuery='.$searchQuery.'"><<</a>';
            $paginationDisplay .= '<a class="pagination-item" href=" '.$_SERVER[ 'PHP_SELF' ].'?page='.$prev.'&searchQuery='.$searchQuery.'"><</a>';
          }

          $paginationDisplay .= $centerPage;

          if ( $page != $lastPage ) {
            $next = $page + 1;
            $paginationDisplay .= '<a class="pagination-item" href=" '.$_SERVER[ 'PHP_SELF' ].'?page='.$next.'&searchQuery='.$searchQuery.'">></a>';
            $paginationDisplay .= '<a class="pagination-item" href="?page='.$lastPage.'&searchQuery='.$searchQuery.'">>></a>';
          }
        }

        echo $paginationDisplay;

      }
      /**********************************************************************/
      //  Looping On Table Header Rows, Drawing & Displaying Header Names.  //
      /**********************************************************************/
      function displayQueryHeader( $result, $page, $recordsPerPage, $sortOrder, $searchQuery ) {

        $fieldCount = $result->field_count;

        // Toggling Sort Order Between ASC & DESC.
        $sortOrder == 'DESC' ? $sortOrder = 'ASC' : $sortOrder = 'DESC';

        // Count Table Fields To Fetch Data.
        for ( $i = 0; $i < $fieldCount; $i++ ) {
          $field = $result->fetch_field();

          // Increment Counter By 1 To Start SortField With "1" Instead Of "0".
          $k = $i + 1;

          // Constructing URL Parameters.
            echo '<th><a href="?page='.$page.'&sortField='.$k.'&sortOrder='.$sortOrder.'&recordsPerPage='.$recordsPerPage.'&searchQuery='.$searchQuery.'">'. $field->name .'</a></th>';
          }
        }

      /***********************************************************************/
      //     Looping On Table Data, Drawing Rows & Displaying Its Data.      //
      /***********************************************************************/
      function displayQueryRows( $result ) {

        $fieldCount = $result->field_count;

        if ( $result->num_rows > 0 ) {
          while( $rows = $result->fetch_array() ) {
            echo '<tr>';
              for ( $i = 0; $i < $fieldCount; $i++) {
                echo '<td>' . $rows[ $i ] . '</td>';
              }
            echo '</tr>';
          }
        } else {
          echo '<h4 class="main">No Result Returned.</h4>';
        }
      }

  ?>
