<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//----------------Dash Menu Contents------------------//
    
    if (isset($_POST['create_ajdwp_like_follow_table'])){

        global $wpdb;

        $table_name = $wpdb->prefix . "ajdwp_like_follow";

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name   (

        num                         bigint(20)      NOT NULL    AUTO_INCREMENT  PRIMARY KEY,
        user_id                     varchar(255)    NOT NULL,
        post_id                     varchar(255)    NOT NULL,
        author_id                   varchar(255)    NOT NULL,
        like_stat                   varchar(255)    NOT NULL,
        follow_stat                 varchar(255)    NOT NULL
        
        ) $charset_collate;";

        require_once( ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta( $sql );

        ?>
        <script>window.location.reload();</script>
        <?php

	}

?>

<div>
    <form  method="post" action="">
        <input type="submit" value="Create Database" name="create_ajdwp_like_follow_table">
    </form>
</div>


