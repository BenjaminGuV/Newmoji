<?php 
    global $wpdb;

    echo add_query_arg( $wpdb->query_vars, home_url( $wpdb->request ) );

    $wp_params_get = array(
        "page_num" => 1
    );

    //init
    $page_current = 1;

    if( isset( $_GET["page_num"] ) ){
        $wp_params_get["page_num"]   = $_GET["page_num"];
        $page_current                = $_GET["page_num"];
    }

    $url_newmoji = NWMJ_NEWMOJI__PLUGIN_URL . 'assets/emojis';

    $table_votes       = $wpdb->prefix . 'newmoji_votes';
    $table_posts       = $wpdb->prefix . 'posts';
    $table_votes_group = $wpdb->prefix . 'newmoji_votes_group';

    $limit_row    = 5;
    

    //start and end limits
    if ( $page_current == 1 ) {
        $start_limit = 0;
        $end_limit   = $limit_row;
    }else{
        $start_limit = ($page_current - 1) * $limit_row;
        $end_limit   = $limit_row;
    }

    $sql_select = sprintf( "SELECT %1\$s.fid_emotion, %1\$s.fid_posts,
                                %2\$s.post_title, COUNT( * ) AS value_emotion
                            FROM %1\$s
                            INNER JOIN %2\$s ON %2\$s.ID = %1\$s.fid_posts
                            GROUP BY %1\$s.fid_posts, %1\$s.fid_emotion;", 
                            $table_votes, $table_posts );


    $sql_select = sprintf( "SELECT SQL_CALC_FOUND_ROWS %1\$s.*, %4\$s.post_title, %4\$s.ID,
                                (reaction_one + reaction_two + reaction_three + reaction_four + reaction_five) AS total_votes
                            FROM %1\$s
                            INNER JOIN %4\$s ON %4\$s.ID = %1\$s.fid_posts
                            LIMIT %2\$d, %3\$d;", 
                            $table_votes_group, $start_limit, $end_limit, $table_posts );

    $prepared_query = $wpdb->prepare( $sql_select );

    $results = $wpdb->get_results( $prepared_query );

    // all rows without filter
    $sql_select_all = sprintf( "SELECT FOUND_ROWS() as row_all;" );

    $prepared_query_select_all = $wpdb->prepare( $sql_select_all );

    $results_select_all = $wpdb->get_results( $prepared_query_select_all );

    $num_row_all = $results_select_all[0]->row_all;
    // / all rows without filter

    $data_emotions = array(
        1 => array( 
            "name"        => "Feliz",
            "value"       => 0,
            "path_imagen" => $url_newmoji . "/feliz.png",
        ),
        2 => array( 
            "name"        => "Alegre",
            "value"       => 0,
            "path_imagen" => $url_newmoji . "/risas.png",
        ),
        3 => array( 
            "name"        => "Da igual",
            "value"       => 0,
            "path_imagen" => $url_newmoji . "/no_me_importa.png",
        ),
        4 => array( 
            "name"        => "Enojo",
            "value"       => 0,
            "path_imagen" => $url_newmoji . "/enojo.png",
        ),
        5 => array( 
            "name"        => "Tristeza",
            "value"       => 0,
            "path_imagen" => $url_newmoji . "/tristeza.png",
        ),
    );

    $emotions_posts_total = array();

    $data_votes_emotions = array();

    if ( !empty( $results ) ) {
        foreach ($results as $key => $value) {
            if ( empty( $data_votes_emotions[ $results[$key]->fid_posts ] ) ) {
                //create array
                $data_votes_emotions[ $results[$key]->fid_posts ] = $data_emotions;

            }

            $data_votes_emotions[ $results[$key]->fid_posts ][ 1 ]["value"] =  $results[$key]->reaction_one;
            $data_votes_emotions[ $results[$key]->fid_posts ][ 2 ]["value"] =  $results[$key]->reaction_two;
            $data_votes_emotions[ $results[$key]->fid_posts ][ 3 ]["value"] =  $results[$key]->reaction_three;
            $data_votes_emotions[ $results[$key]->fid_posts ][ 4 ]["value"] =  $results[$key]->reaction_four;
            $data_votes_emotions[ $results[$key]->fid_posts ][ 5 ]["value"] =  $results[$key]->reaction_five;
            
            $emotions_posts_total[ $results[$key]->fid_posts ] = $results[$key]->total_votes;

        }
    }

?>
<div class="wrap">
    <h1><?php echo __( 'Reaction Statistics!', 'newmoji' ); ?></h1>
    <br>
    <br>
    <div class="row">
        <div class="col-nwe-12">
            <table class="wp-list-table widefat fixed striped table-view-list">
                <thead>
                    <tr>
                        <th><?php echo __( 'Title post', 'newmoji' ); ?></th>
                        <th><?php echo __( 'Total reaction', 'newmoji' ); ?></th>
                        <th><?php echo __( 'Happy', 'newmoji' ); ?></th>
                        <th><?php echo __( 'Funny', 'newmoji' ); ?></th>
                        <th><?php echo __( 'No matter', 'newmoji' ); ?></th>
                        <th><?php echo __( 'Angry', 'newmoji' ); ?></th>
                        <th><?php echo __( 'Sad', 'newmoji' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if ( !empty( $results ) ) {
                            foreach ($results as $key => $value) {
                            ?>
                                <tr>
                                    <td><?php echo $results[$key]->post_title; ?> <?php echo $results[$key]->ID; ?></td>
                                    <td>
                                        <?php echo $emotions_posts_total[ $results[$key]->fid_posts ]; ?>
                                    </td>
                                    <?php
                                        foreach ($data_votes_emotions[ $results[$key]->fid_posts ] as $key2 => $value2) {
                                        ?>
                                            <td>
                                                <img width="10%" src="<?php echo $data_votes_emotions[ $results[$key]->fid_posts ][ $key2 ]["path_imagen"]; ?>" alt="">
                                                <br>
                                                <?php echo $data_votes_emotions[ $results[$key]->fid_posts ][ $key2 ]["value"]; ?>
                                            </td>
                                        <?php
                                        }
                                    ?>                                    
                                </tr>
                            <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-nwe-12">
            <?php
                if ( !empty( $num_row_all / $limit_row ) ) {
                    $num_page = $num_row_all / $limit_row;
                    $num_page = floor( $num_page );

                    if ( $num_row_all % $limit_row !== 0 ) {
                        $num_page = $num_page + 1;
                    }

                    ?>
                    <ul id="newmoji-ul">
                        <?php
                            if ( $page_current != 1 ) {
                                $path_includes_page_admin_int_before = get_site_url() . "/wp-admin/admin.php?page=newmoji%2Fincludes%2Fnewmoji-acp-page.php&page_num=" . ( $page_current - 1);
                            ?>
                                <li class=""><a href="<?php echo $path_includes_page_admin_int_before; ?>"> < </a></li>
                            <?php
                            }
                        ?>
                        <?php
                            for ($i=1; $i <= $num_page; $i++) { 
                                
                                if ( $i == $page_current ) {
                                ?>
                                    <li class=""><?php echo $i; ?></li>
                                <?php
                                } else {
                                    
                                    $path_includes_page_admin_int = get_site_url() . "/wp-admin/admin.php?page=newmoji%2Fincludes%2Fnewmoji-acp-page.php&page_num=" . $i;
                                ?>
                                    <li class=""><a href="<?php echo $path_includes_page_admin_int; ?>"><?php echo $i; ?></a></li>
                                <?php
                                    
                                }
                                
                            }
                        ?>
                        <?php

                            if ( $page_current < $num_page ) {
                                $path_includes_page_admin_int_after = get_site_url() . "/wp-admin/admin.php?page=newmoji%2Fincludes%2Fnewmoji-acp-page.php&page_num=" . ( $page_current + 1);
                            ?>
                            <li class=""><a href="<?php echo $path_includes_page_admin_int_after; ?>"> > </a></li>
                            <?php
                            }
                        ?>
                    </ul>
                    <?php

                }
            ?>
        </div>
    </div>

</div>