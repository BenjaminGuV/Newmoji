<?php 
    global $wpdb;

    $url_newmoji = NWMJ_NEWMOJI__PLUGIN_URL . 'assets/emojis';

    $table_votes = $wpdb->prefix . 'newmoji_votes';
    $table_posts = $wpdb->prefix . 'posts';

    $sql_select = sprintf( "SELECT %1\$s.fid_emotion, %1\$s.fid_posts,
                                %2\$s.post_title, COUNT( * ) AS value_emotion
                            FROM %1\$s
                            INNER JOIN %2\$s ON %2\$s.ID = %1\$s.fid_posts
                            GROUP BY %1\$s.fid_posts, %1\$s.fid_emotion;", 
                            $table_votes, $table_posts );

    $prepared_query = $wpdb->prepare( $sql_select );

    $results = $wpdb->get_results( $prepared_query );

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

            $data_votes_emotions[ $results[$key]->fid_posts ][ $results[$key]->fid_emotion ]["value"] =  $results[$key]->value_emotion; 
            
            $emotions_posts_total[ $results[$key]->fid_posts ] = $emotions_posts_total[ $results[$key]->fid_posts ] + $results[$key]->value_emotion;

        }
    }

?>
<div class="wrap">
    <h1>Reaction Statistics!</h1>
    <br>
    <br>
    <div class="row">
        <div class="col-nwe-12">
            <table class="wp-list-table widefat fixed striped table-view-list">
                <thead>
                    <tr>
                        <th>Title post</th>
                        <th>Total reaction</th>
                        <th>Feliz</th>
                        <th>Alegre</th>
                        <th>Da igual</th>
                        <th>Enojo</th>
                        <th>Tristeza</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if ( !empty( $results ) ) {
                            foreach ($results as $key => $value) {
                            ?>
                                <tr>
                                    <td><?php echo $results[$key]->post_title; ?></td>
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

</div>