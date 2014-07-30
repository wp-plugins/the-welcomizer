<?php
/*  Copyright 2014  Sébastien Laframboise  (email:sebastien.laframboise@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Backward compatibility to v3.1
// Missing functions for v3.1
class TwizCompatibility{

    function __construct(){}

    // Backward compatibility to v3.1
    // /wp-includes/functions.php
    function wp_is_main_network( $network_id = null ) {
    
        global $wpdb;
         
        if ( ! is_multisite() )
            return true;
         
        $current_network_id = (int) get_current_site()->id;
         
        if ( ! $network_id )
            $network_id = $current_network_id;
            $network_id = (int) $network_id;
         
        if ( defined( 'PRIMARY_NETWORK_ID' ) )
            return $network_id === (int) PRIMARY_NETWORK_ID;
         
        if ( 1 === $current_network_id )
            return $network_id === $current_network_id;
         
        $primary_network_id = (int) wp_cache_get( 'primary_network_id', 'site-options' );
         
        if ( $primary_network_id )
            return $network_id === $primary_network_id;
         
        $primary_network_id = (int) $wpdb->get_var( "SELECT id FROM ".$wpdb->site." ORDER BY id LIMIT 1" );
        wp_cache_add( 'primary_network_id', $primary_network_id, 'site-options' );
         
        return $network_id === $primary_network_id;
    }    
    
    // Backward compatibility to v3.1
    // /wp-includes/ms-blogs.php
    function wp_ms_is_switched() { 
    
        return ! empty( $GLOBALS['_wp_switched_stack'] );
    }    
    
    // Backward compatibility to v3.1
    // /wp-includes/ms-functions.php
    function wp_wp_get_sites( $args = array() ) {
    
        global $wpdb;
         
        if ( wp_is_large_network() )
            return array();
         
        $defaults = array(
        'network_id' => $wpdb->siteid,
        'public' => null,
        'archived' => null,
        'mature' => null,
        'spam' => null,
        'deleted' => null,
        'limit' => 100,
        'offset' => 0,
        );
         
        $args = wp_parse_args( $args, $defaults );
         
        $query = "SELECT * FROM ".$wpdb->blogs." WHERE 1=1 ";
         
        if ( isset( $args['network_id'] ) && ( is_array( $args['network_id'] ) || is_numeric( $args['network_id'] ) ) ) {
            $network_ids = implode( ', ', wp_parse_id_list( $args['network_id'] ) );
            $query .= "AND site_id IN ($network_ids) ";
        }
         
        if ( isset( $args['public'] ) )
            $query .= $wpdb->prepare( "AND public = %d ", $args['public'] );
         
        if ( isset( $args['archived'] ) )
            $query .= $wpdb->prepare( "AND archived = %d ", $args['archived'] );
         
        if ( isset( $args['mature'] ) )
            $query .= $wpdb->prepare( "AND mature = %d ", $args['mature'] );
         
        if ( isset( $args['spam'] ) )
            $query .= $wpdb->prepare( "AND spam = %d ", $args['spam'] );
         
        if ( isset( $args['deleted'] ) )
            $query .= $wpdb->prepare( "AND deleted = %d ", $args['deleted'] );
         
        if ( isset( $args['limit'] ) && $args['limit'] ) {
        if ( isset( $args['offset'] ) && $args['offset'] )
            $query .= $wpdb->prepare( "LIMIT %d , %d ", $args['offset'], $args['limit'] );
        else
            $query .= $wpdb->prepare( "LIMIT %d ", $args['limit'] );
        }
         
        $site_results = $wpdb->get_results( $query, ARRAY_A );
         
        return $site_results;
    }  

}?>