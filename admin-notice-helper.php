<?php

/*
Name:        Admin Notice Helper
URI:         https://github.com/wpdevels/admin-notice-helper
Version:     0.1
Author:      Mauricio Gelves
Author URI:  https://wpdevels.es
License:     GPLv2
Forked from: Ian Dunn: https://github.com/iandunn/admin-notice-helper
*/

/*  
 * Copyright 2016 Mauricio Gelves (email : maugelves@wpdevels.es)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

if ( ! class_exists( 'Admin_Notice_Helper' ) ) {

	class Admin_Notice_Helper {
		// Declare variables and constants
		protected static $instance;
		protected $notices;

		/**
		 * Constructor
		 */
		protected function __construct() {
			add_action( 'admin_notices', array( $this, 'print_notices' ) );

			// Initialize variables
			$default_notices             = array( 'update' => array(), 'error' => array() );
			$this->notices               = $default_notices;
		}

		/**
		 * Provides access to a single instances of the class using the singleton pattern
		 *
		 * @author Mauricio Gelves <maugeles@wpdevels.es>
		 * @version 0.1
		 * @return object
		 */
		public static function get_singleton() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new Admin_Notice_Helper();
			}

			return self::$instance;
		}



		/**
		 * Queues up a message to be displayed to the user
		 *
		 * @author Mauricio Gelves <maugeles@wpdevels.es>
		 * @version 0.1
		 * @param string $message The text to show the user
		 * @param string $type    'update' for a success or notification message, or 'error' for an error message
		 */
		public function enqueue( $message, $type = 'update' ) {
			if ( in_array( $message, array_values( $this->notices[ $type ] ) )
			) {
				return;
			}

			$this->notices[ $type ][]   = (string) apply_filters( 'anh_enqueue_message', $message );
		}

		/**
		 * Displays updates and errors
		 * @author Mauricio Gelves <maugeles@wpdevels.es>
		 * @version 0.1
		 */
		public function print_notices() {

			foreach ( array( 'update', 'error' ) as $type ) {
				if ( count( $this->notices[ $type ] ) ) {
					$class = 'update' == $type ? 'updated' : 'error'; ?>

					<div class="anh_message <?php esc_attr_e( $class ); ?>">
						<?php foreach ( $this->notices[ $type ] as $notice ) : ?>
							<p><?php echo wp_kses( $notice, wp_kses_allowed_html( 'post' ) ); ?></p>
						<?php endforeach; ?>
					</div>
					

					<?php
					
					// Reset variables
					$this->notices[ $type ]      = array();
				}
			}
		}
		
	} // end Admin_Notice_Helper

	
	
	
	
	
	
	Admin_Notice_Helper::get_singleton(); // Create the instance immediately to make sure hook callbacks are registered in time

	if ( ! function_exists( 'add_notice' ) ) {
		function add_notice( $message, $type = 'update' ) {
			Admin_Notice_Helper::get_singleton()->enqueue( $message, $type );
		}
	}
}



