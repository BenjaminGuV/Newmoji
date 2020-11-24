<?php
    global $wpdb;

    $table_name             = $wpdb->prefix . "newmoji_votes";
    $my_products_db_version = '1.0.0';
    $charset_collate        = $wpdb->get_charset_collate();

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
                `id_tt_newmoji_votes` INT NOT NULL AUTO_INCREMENT,
                `fid_emotion` INT NULL DEFAULT 0,
                `fid_posts` INT NULL DEFAULT 0,
                `ip` VARCHAR(15) NULL DEFAULT '',
                `navegador` VARCHAR(200) NULL DEFAULT '',
                `content` LONGTEXT,
                `hash_votes` VARCHAR(250) NULL DEFAULT '',
                `date_time` DATETIME,
                PRIMARY KEY  (`id_tt_newmoji_votes`)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        add_option('my_db_version', $my_products_db_version);
    }