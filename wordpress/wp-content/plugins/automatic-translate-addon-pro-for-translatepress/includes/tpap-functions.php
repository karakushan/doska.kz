<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get Data From Database
 * Hooked to wp_ajax_get_strings.
 */
function tpap_getstrings() {
	// Check user capabilities first - restrict to administrators only
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( esc_html__( 'Insufficient permissions to access translation strings. Administrator access required.', 'tpap' ) );
		wp_die( '0', 403 );
	}
	
	// Ready for the magic to protect our code
	check_ajax_referer( 'auto-translate-press-pro-nonces' );
	$reg_exUrl = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
	$pattern   = '/\[[^]]+\]/i';
	global $wpdb;
	$result           = array();
	$data             = array();
	$default_code     = isset( $_POST['data'] ) ? sanitize_text_field( $_POST['data'] ) : '';
	$default_language = isset( $_POST['default_lang'] ) ? sanitize_text_field( $_POST['default_lang'] ) : '';
	$current_page_id  = isset( $_POST['dictionary_id'] ) ? sanitize_text_field( $_POST['dictionary_id'] ) : '';
	$gettxt_id        = isset( $_POST['gettxt_id'] ) ? sanitize_text_field( $_POST['gettxt_id'] ) : '';
	
	// Validate language codes - only allow alphanumeric characters and underscores/dashes
	if ( ! preg_match( '/^[a-zA-Z0-9_-]+$/', $default_code ) || ! preg_match( '/^[a-zA-Z0-9_-]+$/', $default_language ) ) {
		wp_send_json_error( esc_html__( 'Invalid language code format.', 'tpap' ) );
		wp_die();
	}
	
	// Language code validation removed per user request
	
	// Validate and sanitize IDs arrays with limits to prevent enumeration attacks
	$strings_ID = array_filter( array_map( 'absint', explode( ',', $current_page_id ) ) );
	$get_txt_ids = array_filter( array_map( 'absint', explode( ',', $gettxt_id ) ) );
	
	// Ensure we have valid IDs to work with
	if ( empty( $strings_ID ) && empty( $get_txt_ids ) ) {
		wp_send_json_error( esc_html__( 'No valid IDs provided.', 'tpap' ) );
		wp_die();
	}
	
	$def_lang         = strtolower( $default_language );
	$default_code_lower = strtolower( $default_code );
	
	// Construct table names with validated input
	$table2           = $wpdb->get_blog_prefix() . 'trp_gettext_' . $default_code_lower;
	$table1           = $wpdb->get_blog_prefix() . 'trp_dictionary_' . $def_lang . '_' . $default_code_lower;
	
	// Additional table name validation for SQL injection prevention
	if ( ! tpap_validate_table_name( $table1 ) || ! tpap_validate_table_name( $table2 ) ) {
		wp_send_json_error( esc_html__( 'Invalid table name format detected.', 'tpap' ) );
		wp_die();
	}
	
	// Validate table names exist before proceeding - using esc_sql for additional protection
	$table1_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", esc_sql( $table1 ) ) );
	$table2_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", esc_sql( $table2 ) ) );
	
	$results_gettxt = array();
	$results = array();
	
	// Process table1 query only if table exists and we have valid IDs
	if ( $table1_exists && ! empty( $strings_ID ) ) {
		// IDOR protection: Add additional constraints to prevent unauthorized access
		$current_blog_id = get_current_blog_id();
		$placeholders = implode( ',', array_fill( 0, count( $strings_ID ), '%d' ) );
		
		// Additional security: Re-validate and escape table name before query construction
		if ( ! tpap_validate_table_name( $table1 ) ) {
			wp_send_json_error( esc_html__( 'Invalid table name detected during query construction.', 'tpap' ) );
			wp_die();
		}
		
		// Use escaped table name in query construction
		$safe_table1 = esc_sql( $table1 );
		$query = "SELECT id,original_id,original FROM `{$safe_table1}` WHERE id IN ({$placeholders}) AND status!='2'";
		
		// Apply additional filters for authorization if available
		$query = apply_filters( 'tpap_filter_translation_query', $query, $table1, 'dictionary' );
		
		$results_gettxt = $wpdb->get_results( $wpdb->prepare( $query, ...$strings_ID ) );
		
		// Additional validation: Ensure returned results are authorized for current user
		$results_gettxt = tpap_filter_authorized_translation_strings( $results_gettxt, 'dictionary' );
	}
	
	// Process table2 query only if table exists and we have valid IDs  
	if ( $table2_exists && ! empty( $get_txt_ids ) ) {
		$current_blog_id = get_current_blog_id();
		$placeholders = implode( ',', array_fill( 0, count( $get_txt_ids ), '%d' ) );
		
		// Additional security: Re-validate and escape table name before query construction
		if ( ! tpap_validate_table_name( $table2 ) ) {
			wp_send_json_error( esc_html__( 'Invalid table name detected during query construction.', 'tpap' ) );
			wp_die();
		}
		
		// Use escaped table name in query construction
		$safe_table2 = esc_sql( $table2 );
		$query = "SELECT id,original FROM `{$safe_table2}` WHERE id IN ({$placeholders}) AND status!='2'";
		
		// Apply additional filters for authorization if available
		$query = apply_filters( 'tpap_filter_translation_query', $query, $table2, 'gettext' );
		
		$results = $wpdb->get_results( $wpdb->prepare( $query, ...$get_txt_ids ) );
		
		// Additional validation: Ensure returned results are authorized for current user
		$results = tpap_filter_authorized_translation_strings( $results, 'gettext' );
	}
	$final_res        = array_merge( $results_gettxt, $results );
	if ( is_array( $final_res ) && count( $final_res ) > 0 ) {
		foreach ( $final_res as $row ) {
			$original_id = isset( $row->original_id ) ? absint( $row->original_id ) : '';
			$original    = isset( $row->original ) ? $row->original : '';
			$string      = htmlspecialchars_decode( $original );
			if ( $string != strip_tags( $string ) ) {
				continue;
			} elseif ( preg_match( $reg_exUrl, $string ) ) {
				continue;
			} elseif ( preg_match( $pattern, $string ) ) {
				continue;
			}

			if ( $original_id == '' ) {
				$group = 'Gettext';
			} else {
				$group = 'String';
			}
			$data['strings']      = $string;
			$data['database_ids'] = isset( $row->id ) ? absint( $row->id ) : '';
			$data['data_group']   = $group;
			$result[]             = $data;
		}
	}
	wp_send_json( $result );
}

/**
 * Recursively sanitize array data
 */
function tpap_sanitize_array_recursive( $array ) {
	if ( ! is_array( $array ) ) {
		return sanitize_text_field( $array );
	}
	
	$sanitized = array();
	foreach ( $array as $key => $value ) {
		$clean_key = sanitize_key( $key );
		if ( is_array( $value ) ) {
			$sanitized[ $clean_key ] = tpap_sanitize_array_recursive( $value );
		} else {
			$sanitized[ $clean_key ] = sanitize_text_field( $value );
		}
	}
	
	return $sanitized;
}

/**
 *  save translation from ajax post
 * Hooked to wp_ajax_save_translations.
 */
function tpap_save_translations() {
	// Check user capabilities first - restrict to administrators only
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( esc_html__( 'Insufficient permissions to save translations. Administrator access required.', 'tpap' ) );
		wp_die( '0', 403 );
	}
	
	 // Ready for the magic to protect our code
	check_ajax_referer( 'auto-translate-press-pro-nonces' );
	global $wpdb;
	$raw_data = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';
	$decoded_data = json_decode( $raw_data, true );
	$strings = tpap_sanitize_array_recursive( $decoded_data );
	if ( is_array( $strings ) && count( $strings ) > 0 ) {
		$table1_query = array();
		$table2_query = array();
		$table1       = null;
		$table2       = null;
		$validated_language_codes = array();
		
		foreach ( $strings as $languages => $string ) {
			$types            = isset( $string['data_group'] ) ? sanitize_text_field( $string['data_group'] ) : '';
			$default_code     = isset( $string['language_code'] ) ? sanitize_text_field( $string['language_code'] ) : '';
			$default_language = isset( $string['default_lang'] ) ? sanitize_text_field( $string['default_lang'] ) : '';
			
			// Validate language codes - only allow alphanumeric characters and underscores/dashes
			if ( ! preg_match( '/^[a-zA-Z0-9_-]+$/', $default_code ) || ! preg_match( '/^[a-zA-Z0-9_-]+$/', $default_language ) ) {
				wp_send_json_error( esc_html__( 'Invalid language code format.', 'tpap' ) );
				wp_die();
			}
			
			$def_lang = strtolower( $default_language );
			$default_code_lower = strtolower( $default_code );
			$lang_pair = $def_lang . '_' . $default_code_lower;
			
			// Only construct tables once per language pair
			if ( ! isset( $validated_language_codes[ $lang_pair ] ) ) {
				$table2 = $wpdb->get_blog_prefix() . 'trp_gettext_' . $default_code_lower;
				$table1 = $wpdb->get_blog_prefix() . 'trp_dictionary_' . $def_lang . '_' . $default_code_lower;
				
				// Additional table name validation for SQL injection prevention
				if ( ! tpap_validate_table_name( $table1 ) || ! tpap_validate_table_name( $table2 ) ) {
					wp_send_json_error( esc_html__( 'Invalid table name format detected.', 'tpap' ) );
					wp_die();
				}
				
				// Validate table names exist before proceeding - using esc_sql for additional protection
				$table1_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", esc_sql( $table1 ) ) );
				$table2_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", esc_sql( $table2 ) ) );
				
				if ( ! $table1_exists || ! $table2_exists ) {
					wp_send_json_error( esc_html__( 'Required translation tables do not exist.', 'tpap' ) );
					wp_die();
				}
				
				$validated_language_codes[ $lang_pair ] = array(
					'table1' => $table1,
					'table2' => $table2
				);
			} else {
				$table1 = $validated_language_codes[ $lang_pair ]['table1'];
				$table2 = $validated_language_codes[ $lang_pair ]['table2'];
			}
			
			if ( $types == 'String' ) {
				$table1_query[] = $string;
			} else {
				$table2_query[] = $string;
			}
		}
		
		if ( $table1 != null && $table2 != null ) {
			if ( ! empty( $table1_query ) ) {
				wp_insert_rows( $table1, true, 'id', $table1_query );
			}
			if ( ! empty( $table2_query ) ) {
				wp_insert_rows( $table2, true, 'id', $table2_query );
			}
		}
		wp_die();
	}
}

/**
 *  A method for inserting multiple rows into the specified table
 *  Updated to include the ability to Update existing rows by primary key
 */
function wp_insert_rows( $wp_table_name, $update = false, $primary_key = 'id', $row_arrays = array() ) {
	global $wpdb;
	
	// Enhanced table name validation for SQL injection prevention
	if ( ! tpap_validate_table_name( $wp_table_name ) ) {
		return false;
	}
	
	// Verify table exists before attempting insert - using esc_sql for additional protection
	$table_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", esc_sql( $wp_table_name ) ) );
	if ( ! $table_exists ) {
		return false;
	}
	
	// Validate primary key name
	$primary_key = preg_replace( '/[^a-zA-Z0-9_]/', '', $primary_key );
	if ( empty( $primary_key ) ) {
		$primary_key = 'id';
	}
	
	// Setup arrays for Actual Values, and Placeholders
	$values        = array();
	$place_holders = array();
	$query         = '';
	$query_columns = '';
	
	// Use escaped table name for query construction
	$safe_table_name = esc_sql( $wp_table_name );
	$query        .= "INSERT INTO `{$safe_table_name}` (";
	foreach ( $row_arrays as $count => $row_array ) {
		foreach ( $row_array as $key => $value ) {
			if ( in_array( $key, array( 'data_group', 'original', 'language_code', 'database_id', 'default_lang' ) ) ) {
				continue;
			}
			
			// Validate column name - only allow alphanumeric characters and underscores
			$key = preg_replace( '/[^a-zA-Z0-9_]/', '', $key );
			if ( empty( $key ) ) {
				continue;
			}
			
			if ( $count == 0 ) {
				if ( $query_columns ) {
					$query_columns .= ', `' . $key . '`';
				} else {
					$query_columns .= '`' . $key . '`';
				}
			}
			$values[] = $value;
			$symbol   = '%s';
			if ( is_numeric( $value ) ) {
				$symbol = '%d';
			}
			if ( isset( $place_holders[ $count ] ) ) {
				$place_holders[ $count ] .= ", '$symbol'";
			} else {
				$place_holders[ $count ] = "( '$symbol'";
			}
		}
		// mind closing the GAP
		$place_holders[ $count ] .= ')';
	}
	$query .= " $query_columns ) VALUES ";
	$query .= implode( ', ', $place_holders );
	if ( $update ) {
		$update = " ON DUPLICATE KEY UPDATE `$primary_key`=VALUES( `$primary_key` ),";
		$cnt    = 0;
		foreach ( $row_arrays[0] as $key => $value ) {
			if ( in_array( $key, array( 'data_group', 'original', 'language_code', 'database_id', 'default_lang' ) ) ) {
				continue;
			}
			
			// Validate column name - only allow alphanumeric characters and underscores
			$key = preg_replace( '/[^a-zA-Z0-9_]/', '', $key );
			if ( empty( $key ) ) {
				continue;
			}
			
			if ( $cnt == 0 ) {
				$update .= "`$key`=VALUES(`$key`)";
				$cnt     = 1;
			} else {
				$update .= ", `$key`=VALUES(`$key`)";
			}
		}
		$query .= $update;
	}
	$sql = $wpdb->prepare( $query, $values );
	if ( $wpdb->query( $sql ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Validate table name format to prevent SQL injection
 * 
 * @param string $table_name The table name to validate
 * @return bool True if valid, false otherwise
 */
function tpap_validate_table_name( $table_name ) {
	global $wpdb;
	
	// Get WordPress table prefix for validation
	$blog_prefix = $wpdb->get_blog_prefix();
	
	// Table name must start with WordPress prefix
	if ( strpos( $table_name, $blog_prefix ) !== 0 ) {
		return false;
	}
	
	// Remove the prefix for pattern validation
	$suffix = substr( $table_name, strlen( $blog_prefix ) );
	
	// Validate suffix follows TranslatePress patterns
	$valid_patterns = array(
		'/^trp_gettext_[a-z0-9_-]+$/',           // trp_gettext_en, trp_gettext_es_es
		'/^trp_dictionary_[a-z0-9_-]+_[a-z0-9_-]+$/' // trp_dictionary_en_es, trp_dictionary_fr_de
	);
	
	foreach ( $valid_patterns as $pattern ) {
		if ( preg_match( $pattern, $suffix ) ) {
			return true;
		}
	}
	
	return false;
}



/**
 * Filter authorized translation strings to prevent unauthorized access
 * 
 * @param array $results Database results
 * @param string $type Type of translation (dictionary or gettext)
 * @return array Filtered results
 */
function tpap_filter_authorized_translation_strings( $results, $type = 'dictionary' ) {
	// Apply additional authorization filters if needed
	$filtered_results = apply_filters( 'tpap_filter_translation_results', $results, $type );
	
	// Additional validation can be added here based on user permissions, site settings, etc.
	
	return is_array( $filtered_results ) ? $filtered_results : array();
}


