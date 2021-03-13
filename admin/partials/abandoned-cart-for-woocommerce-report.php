<?php
/**
 * Provide woocommerce reports of abandoned carts.
 *
 * This file is used to show reports ad details of the abandoned carts.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}


if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	}

	?>
		<div class="wrap">
					<h2>All Abandoned Carts</h2>

					<div id="poststuff">
						<div id="post-body" class="metabox-holder columns-4">
							<div id="post-body-content">
								<div class="meta-box-sortables ui-sortable">
									<form method="post">
										<?php
										$obj = new Mwb_List_Table();

										$obj->prepare_items();
										echo '<form method="POST" name="mwb_abandon_data_search" action=' . $_SERVER['PHP_SELF'] . '?page=abandoned-cart-for-woocommerce_menu&m_tab=abandoned-cart-for-woocommerce-analytics';
										$obj->search_box( 'Search Data', 'mwb_search_data_id' );
										echo '</form>';
										echo '<form>';
										$obj->display();
										echo '</form>';

										// $obj->delete_data();
										?>
									</form>
								</div>
							</div>
						</div>
						<br class="clear">
					</div>
				</div>



	<?php

	/**
	 * Class Name Mwb_List_Table
	 * This class will show the details of abandoned carts by extending WP_List_Table.
	 */
	class Mwb_List_Table extends WP_List_Table {

		/**
		 * Function name mwb_abandon_cart_data().
		 *
		 * This Function is used to fetch data from the database.
		 *
		 * @param string $orderby sorting order by column.
		 * @param string $order sorting order.
		 * @param [type] $search_item item to search.
		 * @return array
		 */
		public function mwb_abandon_cart_data( $orderby = '', $order = '', $search_item ) {
			global $wpdb;
			$data_arr = array();


			if ( ! empty( $search_item ) ) {
			$result  = $wpdb->get_results( "SELECT * FROM mwb_abandoned_cart WHERE ( email LIKE '%$search_item%' OR cart LIKE '%$search_item%' ) " );
			// echo '<pre>'; print_r( $result ); echo '</pre>';
			// die;
			} elseif( isset( $_GET['orderby'] ) ){
				$result = $wpdb->get_results( 'SELECT * FROM mwb_abandoned_cart ORDER BY ' . $orderby . ' ' . $order . '' );

			} else {
				$result  = $wpdb->get_results( 'SELECT * FROM mwb_abandoned_cart ' );

			}
			if ( count( $result ) > 0 ) {
				foreach ( $result as $key => $value ) {

						$data_arr[] = array (
							'id'     => $value->id,
							'email'  => $value->email,
							'left_page'   => $value->left_page,
							'cart_status' => $value->cart_status,

						);

				}
			}

			return $data_arr;

		}
	

		public function get_hidden_columns() {
			return array();

		}
		/**
		 * Function name get_sortable_columns
		 * This function is used to craete columns as sortable.
		 *
		 * @return array
		 */
		public function get_sortable_columns() {
				return array(
					'id' => array( 'id', true ),
					'email' => array( 'email', true ),
					'cart_status' => array( 'cart_status', true ),
				);
		}

		/**
		 * Function name get_columns
		 * This function is used get all columns from data.
		 *
		 * @return $columns
		 */
		public function get_columns() {
			$columns = array(
				'cb'      => '<input type="checkbox" />',
				'id' => 'ID',
				'email' => 'Email',
				'left_page' => 'Left Page',
				'cart_status' => 'Status',
			);
			return $columns;

		}
		public function column_cb( $item) {
			return sprintf(
				'<input type="checkbox" name="bulk_delete[]" value="%s" />',
					$item['id']
			);
		}
		

		/**
		 * Column Deafult.
		 *
		 * @param [type] $item
		 * @param [type] $columns
		 * @return void
		 */
		public function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'id':
				case 'email':
				case 'left_page':
				case 'cart_status':
				case 'action':
					return $item[ $column_name ];
				default:
					return 'No Value';
			}

		}

		/**
		 * Function name column_email
		 * this function will show email columns
		 *
		 * @param [type] $item contains item.
		 * @return array
		 */
		public function column_email( $item ) {

			$action = array(
				'view' => '<a href="javascript:void(0)" id="view_data" data-id="' . $item['id'] . '">View</a>',
				);
			return sprintf( '%1$s %2$s', $item['email'], $this->row_actions( $action ) );
		}


		

		/**
   * Returns an associative array containing the bulk action
   *
   * @return array
   */
  public function get_bulk_actions()
  {
    $actions = [
      'bulk-delete' => 'Delete'
    ];

    return $actions;
  }

		public static function delete_cart($id)
		{
		  global $wpdb;
		  $table_name = 'mwb_abandoned_cart';
	  
		  $wpdb->delete(
			"$table_name",
			['id' => $id],
			['%d']
		  );
		  
		  
		}

		public function process_bulk_action() {
			//Detect when a bulk action is being triggered...
			if ( 'delete' === $this->current_action() ) {
		  
			  // In our file that handles the request, verify the nonce.
			  $nonce = esc_attr( $_REQUEST['_wpnonce'] );
		  
			  if ( ! wp_verify_nonce( $nonce, 'sp_delete_cart' ) ) {
				die( 'Go get a life script kiddies' );
			  }
			  else {
				self::delete_cart( absint( $_GET['id'] ) );
		  
				// wp_redirect( esc_url( add_query_arg() ) );
				exit;
			  }
		  
			}
		  
			// If the delete bulk action is triggered
			if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
				 || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
			) {
		  
			  $delete_ids = esc_sql( $_POST['bulk-delete'] );
		  
			  // loop over the array of record IDs and delete them
			  foreach ( $delete_ids as $id ) {
				self::delete_cart( $id );
		  
			  }
		  
			  wp_redirect( esc_url( add_query_arg() ) );
			  exit;
			}
		  }

			/**
		 * Function to prepare items
		 *
		 * @return void
		 */
		public function prepare_items() {

		

			$search_item = isset( $_POST['s'] ) ? trim( $_POST['s'] ) : '';
			$orderby = isset( $_GET['orderby'] ) ? trim( $_GET['orderby'] ) : '';
			$order = isset( $_GET['order'] )?trim( $_GET['order'] ) : '';


			$mwb_all_data = $this->mwb_abandon_cart_data( $orderby, $order, $search_item );
			$per_page = 3;
			$current_page = $this->get_pagenum();
			$total_data = count( $mwb_all_data );
			$this->set_pagination_args(
				array(
					'total_items' => $total_data,
					'per_page' => $per_page,
				)
			);
			$this->items = array_slice( $mwb_all_data, ( ( $current_page - 1 ) * $per_page ), $per_page );
			// callback to get columns.
			$columns = $this->get_columns();
			// callback to get hidden columns.
			$hidden = $this->get_hidden_columns();
			// callback to get sortable columns.
			$sortable = $this->get_sortable_columns();
			$this->process_bulk_action();
			// all callback called to the header.
			$this->_column_headers = array( $columns, $hidden, $sortable );


		}

	}
	?>
	<div id="view" title="Full Details Of Cart">
	<p id="show_table"></p>
	</div>
	<?php
