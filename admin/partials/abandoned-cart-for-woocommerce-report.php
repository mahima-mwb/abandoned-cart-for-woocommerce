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

?>


<?php
global $acfw_mwb_acfw_obj;
$acfw_active_tab   = isset( $_GET['acfw_tab'] ) ? sanitize_key( $_GET['acfw_tab'] ) : 'abandoned-cart-for-woocommerce-general';
$acfw_default_tabs = $acfw_mwb_acfw_obj->mwb_acfw_plug_default_sub_tabs();

?>

<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<ul class="mwb-navbar__items">
			<?php
			if ( is_array( $acfw_default_tabs ) && ! empty( $acfw_default_tabs ) ) {

				foreach ( $acfw_default_tabs as $acfw_tab_key => $acfw_default_tabs ) {

					$acfw_tab_classes = 'mwb-link ';

					if ( ! empty( $acfw_active_tab ) && $acfw_active_tab === $acfw_tab_key ) {
						$acfw_tab_classes .= 'active';
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $acfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=abandoned_cart_for_woocommerce_menu' ) . '&acfw_tab=' . esc_attr( $acfw_tab_key ) ); ?>" class="<?php echo esc_attr( $acfw_tab_classes ); ?>"><?php echo esc_html( $acfw_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
</main>
		<div class="wrap">
					<div id="poststuff">
						<div id="post-body" class="metabox-holder columns-4">
							<div id="post-body-content">
								<div class="meta-box-sortables ui-sortable">
									<form method="post">
										<?php
										$obj = new Abandoned_Cart_For_Woocommerce_Report();
										$obj->prepare_items();
										echo '<form method="POST" name="mwb_abandon_data_search" action=' . $_SERVER['PHP_SELF'] . '?page=abandoned-cart-for-woocommerce_menu&m_tab=abandoned-cart-for-woocommerce-analytics'; //phpcs:ignore
										$obj->search_box( 'Search by email', 'mwb_search_data_id' );
										echo '</form>';
										echo '<form method="POST">';
										$obj->display();
										echo '</form>';
										?>
									</form>
								</div>
							</div>
						</div>
						<br class="clear">
					</div>
				</div>

				<?php

				if ( ! class_exists( 'WP_List_Table' ) ) {
						require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
				}

				?>

	<?php


	/**
	 * Class Name Mwb_List_Table
	 * This class will show the details of abandoned carts by extending WP_List_Table.
	 */
	class Abandoned_Cart_For_Woocommerce_Report extends WP_List_Table {

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
		public function mwb_abandon_cart_data( $orderby, $order, $search_item ) {
			global $wpdb;
			$data_arr = array();

			if ( ! empty( $search_item ) ) {
				$result  = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "mwb_abandoned_cart WHERE cart_status != 0 AND ( email LIKE '%$search_item%' OR cart LIKE '%$search_item%' ) " ); //phpcs:ignore
			} elseif ( isset( $_GET['orderby'] ) && isset( $_GET['order'] ) ) {
				$order_show = sanitize_text_field( wp_unslash( $_GET['order'] ) );
				$order_by =  sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
				if ( 'email' === $order_by && 'asc' === $order_show ) {
					$result = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'mwb_abandoned_cart WHERE cart_status != 0 ORDER BY email asc ' );
				} elseif ( 'email' === $order_by && 'desc' === $order_show ) {
					$result = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'mwb_abandoned_cart WHERE cart_status != 0 ORDER BY email desc ' );
				} elseif ( 'total' === $order_by && 'asc' === $order_show ) {
					$result = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'mwb_abandoned_cart WHERE cart_status != 0 ORDER BY total asc ' );
				} elseif ( 'total' === $order_by && 'desc' === $order_show ) {
					$result = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'mwb_abandoned_cart WHERE cart_status != 0 ORDER BY total desc ' );
				} elseif ( 'cart_status' === $order_by && 'asc' === $order_show ) {
					$result = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'mwb_abandoned_cart WHERE cart_status != 0 ORDER BY cart_status asc ' );
				} elseif ( 'cart_status' === $order_by && 'desc' === $order_show ) {
					$result = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'mwb_abandoned_cart WHERE cart_status != 0 ORDER BY cart_status desc ' );
				}
			} else {
				$result  = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'mwb_abandoned_cart WHERE cart_status != 0 ' );

			}
			if ( count( $result ) > 0 ) {
				foreach ( $result as $key => $value ) {

					$status = $value->cart_status;
					if ( '1' === $status ) {
						$status_new = __( 'Abandoned', ' abandoned-cart-for-woocommerce' );
					} elseif ( '2' === $status ) {
						$status_new = __( 'Recovered', ' abandoned-cart-for-woocommerce' );
					}

					$data_arr[] = array(
						'id'     => $value->id,
						'email'     => $value->email,
						'left_page'   => $value->left_page,
						'cart_status' => $status_new,
						'total'       => $value->total,

					);
				}
			}

			return $data_arr;

		}


		/**
		 * Function name get_hidden_columns.
		 * this function will be used for getting hidden coloumns
		 *
		 * @return array
		 * @since             1.0.0
		 */
		public function get_hidden_columns() {
			return array( 'id' );

		}
		/**
		 * Function name get_sortable_columns
		 * This function is used to craete columns as sortable.
		 *
		 * @return array
		 * @since             1.0.0
		 */
		public function get_sortable_columns() {
				return array(
					'email'       => array( 'email', true ),
					'cart_status' => array( 'cart_status', true ),
					'total'       => array( 'total', true ),
				);
		}

		/**
		 * Function name get_columns
		 * This function is used get all columns from data.
		 *
		 * @return $columns
		 * @since             1.0.0
		 */
		public function get_columns() {
			$currency = get_option( 'woocommerce_currency' );
			$columns = array(
				'cb'          => '<input type="checkbox" />',
				'id'          => __( 'ID', 'abandoned-cart-for-woocommerce' ),
				'email'       => __( 'Email', 'abandoned-cart-for-woocommerce' ),
				'left_page'   => __( 'Left Page FROM ', 'abandoned-cart-for-woocommerce' ),
				'total'       => __( 'Total', 'abandoned-cart-for-woocommerce' ) . $currency,
				'cart_status' => __( 'Status', 'abandoned-cart-for-woocommerce' ),
			);
			return $columns;

		}
		/**
		 * Function name column_cb
		 * this function is used to show chekbox
		 *
		 * @param [type] $item contains columns.
		 * @return array
		 * @since             1.0.0
		 */
		public function column_cb( $item ) {
			return sprintf(
				'<input type="checkbox" name="bulk-delete[]" value="%s" />',
				$item['id']
			);
		}


		/**
		 * Function name column_default.
		 * this function is used to find the data of the columns
		 *
		 * @param [type] $item contains item.
		 * @param [type] $column_name contains column name.
		 * @return array
		 * @since             1.0.0
		 */
		public function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'id':
				case 'email':
				case 'left_page':
				case 'cart_status':
				case 'total':
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
		 * @since             1.0.0
		 */
		public function column_email( $item ) {

			$action = array(
				'view' => '<a href="javascript:void(0)" id="view_data" data-id="' . $item['id'] . '">View</a>',
			);
			return sprintf( '%1$s %2$s', $item['email'], $this->row_actions( $action ) );
		}




		/**
		 * Function name get_bulk_actions.
		 * This Function is used to get the bulk action
		 *
		 * @return array
		 * @since             1.0.0
		 */
		public function get_bulk_actions() {
			$actions = array(
				'bulk-delete'    => 'Delete',
			);
			return $actions;
		}

		/**
		 * Function name delete_cart
		 * this function is used to delete cart data .
		 *
		 * @param [type] $id stores the id.
		 * @return void
		 * @since             1.0.0
		 */
		public static function delete_cart( $id ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'mwb_abandoned_cart';

			$wpdb->delete(
				"$table_name",
				array( 'id' => $id ),
				array( '%d' )
			);

		}

		/**
		 * Function name process_bulk_action
		 * this function is used to process bulk action
		 *
		 * @return void
		 * @since             1.0.0
		 */
		public function process_bulk_action() {

			if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' ) ) { //phpcs:ignore

				$delete_ids = esc_sql( $_POST['bulk-delete'] );  //phpcs:ignore
				// loop over the array of record IDs and delete them.
				foreach ( $delete_ids as $id ) {
					self::delete_cart( $id );

				}
				wp_redirect( add_query_arg( get_site_url() . 'admin_url( ?page=abandoned_cart_for_woocommerce_menu&acfw_tab=abandoned-cart-for-woocommerce-report' ) );
				exit;
			}
		}

			/**
			 * Function to prepare items
			 *
			 * @return void
			 * @since             1.0.0
			 */
		public function prepare_items() {

			$search_item = isset( $_POST['s'] ) ? trim( $_POST['s'] ) : '';  //phpcs:ignore
			$orderby = isset( $_GET['orderby'] ) ? trim( $_GET['orderby'] ) : '';  //phpcs:ignore
			$order = isset( $_GET['order'] ) ? trim( $_GET['order'] ) : '';  //phpcs:ignore

			$mwb_all_data = $this->mwb_abandon_cart_data( $orderby, $order, $search_item );
			$per_page     = 20;
			$current_page = $this->get_pagenum();
			$total_data   = count( $mwb_all_data );
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
