<?php

/*
Plugin Name: simpleImportWP
Plugin URI: https://github.com/Oljacic/simpleImportWP
Description: Plugin for CSV import
Version: 0.1
Author: Stef
Author URI: none
*/

// Create a new table
function plugin_table(){

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $tablename = $wpdb->prefix."peoplestats";

    $sql = "CREATE TABLE $tablename (
        id mediumint(11) NOT NULL AUTO_INCREMENT,
        name varchar(80) NOT NULL,
        email varchar(80) NOT NULL,
        age smallint(3) NOT NULL,
        workout_type varchar(80) NOT NULL,
        distance varchar(80) NOT NULL,
        exercise_time varchar(80) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

}
register_activation_hook( __FILE__, 'plugin_table' );

// Add menu
function import_menu() {

   add_menu_page("Import Client Stats", "Import Client Stats" ,"manage_options", "simpleImportWP", "displayList");

}

add_action("admin_menu", "import_menu");

function displayList(){
   include "displaylist.php";
}