<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Sorting</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			/* Color Palette: #010101 | #69779B | #ACDBDF | #F0ECE2 */
			* { box-sizing: border-box; }
			:root { --primary: #69779B; --secondary: #ACDBDF; --shadow: #010101; --link: #F0ECE2; }
			body { color: var(--shadow); font-family: 'Lobster', 'Roboto'; font-size: 1.3rem; text-align: center; margin: 2rem auto; width: 100%; }
			.container { width: inherit; }
			.main { color: var(--primary); }
			a { color: var(--primary); text-decoration: none; }
			a:hover { color: var(--shadow); transition: all 0.6s ease-in-out; }
			hr { border: 1px solid var(--primary); width: 90%; }
			/* Table */
			table { border-collapse: collapse; width: 85%; text-align: center; margin: 1rem auto; }
			th { padding: 0.8rem; background-color: var(--primary); }
			th > a { color: var(--link); text-decoration: none; }
			th > a:hover { color: var(--shadow); transition: all 0.6s ease-in-out; }
			tr:nth-child(even) { background-color: var(--link); }
			/* Arrows */
			/* .arrow-down:after { content: '\25bc'; padding-left: 0.5em; } }
			.arrow-up:after { content: '\25b2'; padding-left: 0.5em; } } */
			/* Pagination */
			.pagination-container { display: inline-flex; font-size: 1.3rem; border-radius: 15px; margin-top: 1rem; }
			.pagination { margin: -2px 0; padding: 5px 0; }
			.pagination-item { border-radius: 20px; text-decoration: none; color: var(--shadow); margin: -1.5px; padding: 5px; border: 1.4px solid var(--primary); background-color: var(--link); }
			.pagination-item:hover, .active { background-color: var(--primary); color: var(--secondary); text-decoration: underline; transition: all 0.6s ease-in-out; }
			.active { border: 4px double var(--secondary); pointer-events: none; }
			.disabled { background-color: var(--primary); color: var(--secondary); pointer-events: none; opacity: 0.5; }
			.hidden { visibility: hidden; display: none; }
			/* Input Fields */
			select { cursor: pointer; font-family: inherit; font-size: 1.1rem; background-color: var(--link); color: var(--primary); padding: 0.5rem; border-radius: 7px; margin: 1rem; transition: all 0.6s ease-in-out; transition: all 0.6s ease-in-out; }
			button { background-color: var(--link); border: 0.2rem double var(--link); border-radius: 0.3rem; color: var(--shadow); cursor: pointer; font-family: inherit; font-size: 1.1rem; line-height: 1.3rem; padding: 0.5rem; }
			button:hover { background-color: var(--primary); border: 0.2rem double var(--secondary); color: var(--secondary); transition: all 0.6s ease-in-out; transition: all 0.6s ease-in-out; }
			/* Filter */
			.filter-container { margin-top: 1rem; }
			input { background-color: var(--link);  border: 0.15rem solid var(--link); border-radius: 0.3rem; font-family: inherit; font-size: 1.2rem; line-height: 1.2rem; margin-right: 1rem;  padding: 0.5rem; }
			input:focus { background-color: var(--secondary); border: 0.15rem solid var(--link); color: var(--shadow); transition: all 0.6s ease-in-out; }
		</style>
	</head>
	<body>

		<?php

			// function connection_string( $query ) {

			// Creating a new connection.
			$connection = new mysqli( 'localhost', 'root', '', 'targetdb' );

			// Checking connection availability.
			if ( $connection->connect_error ) {
				die( '<b>Failed to establish connection</b>' . connection_error() );
			}

				// $result = $connection->query( $query );

				// return $result;
			// }

			// $search_result =  connection_string( $query );


			/****************************************************************/
			/*---------------------- GET VARIABLES -------------------------*/
			/****************************************************************/
			
			// Retreiving number of records page.
			if ( isset( $_GET[ 'records_per_page' ] ) ) {
				$records_per_page = $_GET[ 'records_per_page' ];
			} else {
				$records_per_page = 10;
			}

			// Retreiving page number "if any".
			if ( isset( $_GET[ 'page_num' ] ) ) {
				$page_num = $_GET[ 'page_num' ];
				if ( $page_num <= 0 ) {
					$page_num = 1;
				}
			} else {
				$page_num = 1;
			}

			// Retreiving number of records per page.
			if ( $page_num == '' || $page_num == 1 ) {
				$offset = 0;
			} else {
				$offset = ( $page_num * $records_per_page ) - $records_per_page;
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

			if ( isset( $_GET[ 'submit' ] )) {
				$selected_items = $_GET[ 'records_per_page' ];
				$records_per_page = $selected_items;
				// $submit = $selected_items;


				// Search
				// $value_to_search = $_GET['search_query'];
				// $query = "SELECT ClientID, ClientName, ClientAddress
				//           FROM `clients`
				//           WHERE CONCAT( ClientID, ClientName, ClientAddress )
				//           LIKE '%" . $value_to_search . "%'";

				// $search_result = $connection->query( $query );

				// $search_result =  connection_string( $query );

			}
			else {
				$records_per_page = $records_per_page;
				$selected_items = $records_per_page;
				$submit = $selected_items;

				// $query = 'SELECT ClientID, ClientName, ClientAddress FROM `clients`';
				// $search_result = $connection->query( $query );

			}

    /**************************************************************************************************/

			echo '<div class="container">';

				echo '<h2><a href="./">Sorting</a></h2>';
				echo '<hr>';

				$sql = sql_constructor( $sort_field, $sort_order, $offset, $records_per_page );

				$result = $connection->query( $sql );

				// $result = connection_string( $sql );

				// Pagination Links
				$page_sql = "SELECT ClientID, ClientName, ClientAddress FROM Clients";
				$data = $connection->query( $page_sql );
				$result_set = $data->num_rows;
				$records_per_page = ceil( $result_set / $records_per_page );


				// pagination( $page_num, $records_per_page, $selected_items, $sort_field, $sort_order );
				pagination( $page_num, $records_per_page, $sort_field, $sort_order );



				// var_dump($records_per_page);

				echo '<div class="filter-container">';

					echo '<form method="GET">';

						echo '<select name="records_per_page">';

							for ( $i = 1; $i < $records_per_page; $i++ ) {
								// $j = $i + 1;
								$k = ( $i * 10 );

								if ( $k == $selected_items ) {
									echo '<option value"'. $i . '" selected="selected">' . $k . '</option>';
								} else {
									echo '<option value"'. $i . '">' . $k . '</option>';
								}

							}

						echo '</select>';

						echo '<input type="text" name="search_query" placeholder="Search for a Client">';


						echo '<button type="submit" name="submit">Search</button>';

					echo '</form>';

				echo '</div>';






				echo '<table>';
					display_header( $result, $page_num, $records_per_page, $sort_order );
					display_rows( $result );
				echo '</table>';

				echo '<hr>';

			echo '</div>';

			$connection->close();

			/****************************************************************/
			/*------------------------ FUNCTIONS ---------------------------*/
			/****************************************************************/

			function sql_constructor( $sort_field, $sort_order, $offset, $records_per_page ) {
				$sql = "SELECT ClientID, ClientName, ClientAddress FROM `clients`";
				$sql .= " ORDER BY $sort_field $sort_order";
				$sql .= " LIMIT $offset, $records_per_page";

				return ( $sql );
			}

			/*********************************************************************/
			//       Looping on items to make the equivelent page numbers.       //
			/*********************************************************************/
			function pagination( $page_num, $records_per_page, $sort_field, $sort_order ) {
				$prev = $page_num - 1;
				$next = $page_num + 1;
					echo '<div class="pagination-container">';
						echo '<div class="pagination">';

					// Toggle Prev button according to number of pages, & checking if it has sort_field & sort_order.
					if ( $page_num == '' || $page_num == 1 ) {
						// echo '<a class="pagination-item hidden" ' . $prev . '">Prev</a>';
						echo '<a class="pagination-item hidden" href="?page_num='.$prev.'">Prev</a>';

					}
					else {
						if ( $sort_field == 1 && $sort_order == 'ASC' ) {
							echo '<a class="pagination-item" href="?page_num=' . $prev . '&records_per_page=' . $records_per_page . '">Prev<a>';
						} else {
							echo '<a class="pagination-item" href="?page_num=' . $prev . '&records_per_page=' . $records_per_page . '&sort_field=' . $sort_field . '&sort_order=' . $sort_order . '">Prev</a>';
						}
					}

					for ( $i = 0; $i < $records_per_page; $i++ ) {
						$j = $i + 1;
						// Adding active class to the current page number.
						if ( $j == $page_num ) {
							echo '<a class="pagination-item active" href="?page_num=' . $j . '&records_per_page=' . $records_per_page . '">' . $j . '</a>';
						} else {
							echo '<a class="pagination-item" href="?page_num=' . $j . '&records_per_page=' . $records_per_page . '">' . $j . '</a>';
						}
					}

					// Toggle Next button according to number of pages, & checking if it has sort_field & sort_order.
					if ( $page_num >= $records_per_page ) {
						echo '<a class="pagination-item hidden"' . $next . ' ">Next</a>';

						$page_num == $records_per_page;

					} else {
						if ( $sort_field == 1 && $sort_order == 'ASC' ) {
							echo '<a class="pagination-item" href="?page_num=' . $next . '&records_per_page=' . $records_per_page . '">Next</a>';
						} else {
							echo '<a class="pagination-item" href="?page_num=' . $next . '&records_per_page=' . $records_per_page . '&sort_field=' . $sort_field . '&sort_order=' . $sort_order . '">Next</a>';
						}
					}
				echo '</div>
			</div>';
			}

			/*********************************************************************/
			// Looping on table header rows, drawing & displaying header names.  //
			/*********************************************************************/

			function display_header( $result, $page_num, $records_per_page, $sort_order ) {
				$field_cnt = $result->field_count;

				// Check & Toggling sort order between ASC & DESC.
				$sort_order == 'DESC' ? $sort_order = 'ASC' : $sort_order = 'DESC';

				for ( $i = 0; $i < $field_cnt; $i++ ) {
					$field = $result->fetch_field();
					// incrementing counter by 1 to start with "1" instead of "0".
					$k = $i + 1;


					// Check for page number, to print Out the clickable column names.
					if ( $page_num > $records_per_page ) {
						echo '<th><a class="disabled" href="?page_num=' . $page_num . '&sort_field=' . $k . '&sort_order='. $sort_order .'">' . $field->name . '</a></th>';
					} else {
						echo '<th><a href="?page_num=' . $page_num . '&sort_field=' . $k . '&sort_order=' . $sort_order . '">' . $field->name . '</a></th>';
					}
				}
			}

			/********************************************************************/
			//    Looping on table data, drawing rows & displaying its data.    //
			/*********************************************************************/

			function display_rows( $result ) {
				$field_cnt = $result->field_count;

				// Checking if table has rows or not, if it has; draw the table rows, table data tags & fetch data in, else return a message.
				if ( $result->num_rows > 0 ) {
					while ( $rows = $result->fetch_array() ) {
						echo '<tr>';
							for ( $j = 0; $j < $field_cnt; $j++ ) {
								echo '<td>' . $rows[ $j ] .'</td>';
							}
						echo '</tr>';
					}
				} else {
					echo '<h4 class="main">No Result Returned.</h4>';
				}
			}

		?>
	</body>
</html>
