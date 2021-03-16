<?php
    global $wpdb;

    $table_name             = $wpdb->prefix . "newmoji_votes";
    $table_name_group       = $wpdb->prefix . "newmoji_votes_group";
    $my_products_db_version = '1.1.0';
    $charset_collate        = $wpdb->get_charset_collate();

    $ban_create = false;

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
                `id_newmoji_votes` INT NOT NULL AUTO_INCREMENT,
                `fid_emotion` INT NULL DEFAULT 0,
                `fid_posts` INT NULL DEFAULT 0,
                `ip` VARCHAR(15) NULL DEFAULT '',
                `navegador` VARCHAR(200) NULL DEFAULT '',
                `content` LONGTEXT,
                `hash_votes` VARCHAR(250) NULL DEFAULT '',
                `date_time` DATETIME,
                PRIMARY KEY  (`id_newmoji_votes`)
        ) $charset_collate;";

        //require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        add_option('my_db_version', $my_products_db_version);

        $ban_create = true;
    }


    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name_group}'") != $table_name_group ) {
        $sql = "CREATE TABLE $table_name_group (
                `id_newmoji_votes_group` INT NOT NULL AUTO_INCREMENT,
                `fid_posts` INT NULL DEFAULT 0,
                `reaction_one` INT NULL DEFAULT 0,
                `reaction_two` INT NULL DEFAULT 0,
                `reaction_three` INT NULL DEFAULT 0,
                `reaction_four` INT NULL DEFAULT 0,
                `reaction_five` INT NULL DEFAULT 0,
                PRIMARY KEY  (`id_newmoji_votes_group`)
        ) $charset_collate;";

        //require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        add_option('my_db_version', $my_products_db_version);

        $ban_create = true;
    }


    if ( $ban_create ) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    }