<?php

if ( !function_exists( 'nwmj_newmoji_add_my_admin_link' ) ) {

  function nwmj_newmoji_add_my_admin_link()
  {

      $path_includes_page_admin = plugin_dir_path(__FILE__) . "newmoji-acp-page.php";
  
      add_menu_page(
          'Newmoji', // Title of the page
          'Newmoji', // Text to show on the menu link
          'manage_options', // Capability requirement to see the link
          $path_includes_page_admin // The 'slug' - file to display when clicking the link
      );
  }

}

if ( !function_exists( 'nwmj_newmoji_print_html' ) ) {

  function nwmj_newmoji_print_html ( $content ) {
    global $wpdb;

    $path_browser_client = NWMJ_NEWMOJI__PLUGIN_DIR . "libs/NWMJBrowserClient.php";
    $path_ip_client      = NWMJ_NEWMOJI__PLUGIN_DIR . "libs/NWMJIPClient.php";

    require_once ( $path_browser_client );
    require_once ( $path_ip_client );
    
    $browser = new NWMJBrowserClient();
    $ip      = new NWMJIPClient();
    
    $html_votes_exist = "";
    $html_votes       = "";
    
    //$url_newmoji = WP_PLUGIN_URL . '/newmoji/assets/emojis';
    $url_newmoji = NWMJ_NEWMOJI__PLUGIN_URL . 'assets/emojis';

    
    //if exists
    $ips          = $ip->getIP();
    
    $content_browser      = array( 
      'browser_name'       => $browser->getBrowser(),
      'browser_version'    => $browser->getVersion(),
      'browser_user_agent' => $browser->getUserAgent(),
      'platform'           => $browser->getPlatform(),
    );

    $content_txt  = implode( "|", $content_browser );

    $txt_hash = sprintf( "%d|%s|%s", get_the_ID(), $ips, $content_txt );

    $h_hash = openssl_encrypt( $txt_hash, "AES-128-ECB", "6GaVg3.-m80hgKS" );

    //show mysql errors
    //$wpdb->show_errors( true );

    $sql_select = sprintf( "SELECT *
                            FROM %s
                            WHERE fid_posts = %d AND hash_votes = '%s'
                            LIMIT 1;", $wpdb->prefix . 'newmoji_votes', get_the_ID(), $h_hash );

    $prepared_query = $wpdb->prepare( $sql_select );

    $results = $wpdb->get_results( $prepared_query );

    /*
    //show errors
    if($wpdb->last_error !== '') :
        $wpdb->print_error();
    endif;
    */

    //if already votes
    if ( !empty( $results ) ) {
      
      ob_start();
      ?>
        <div class="row">
          <div class="col-nwe-12">
            <p class="p-votes">Usted ya voto</p>
          </div>
        </div>
      <?php
      $html_votes_exist = ob_get_contents();
      ob_end_clean();
  
      //return $content .= $html;
    }

    //checks votes and print
    $sql_search_votes = sprintf( "SELECT fid_emotion, COUNT(*) AS num_votes_emoticons
                                  FROM tt_newmoji_votes
                                  WHERE fid_posts = %d
                                  GROUP BY fid_emotion;", get_the_ID() );

    $prepared_query = $wpdb->prepare( $sql_search_votes );

    $results_votes = $wpdb->get_results( $prepared_query );

    $a_emotions = array(
      1 => 0,
      2 => 0,
      3 => 0,
      4 => 0,
      5 => 0
    );

    if ( !empty( $results_votes ) ) {
      foreach ($results_votes as $key => $value) {
        $tmp_fid_emotion         = $results_votes[$key]->fid_emotion;
        $tmp_num_votes_emoticons = $results_votes[$key]->num_votes_emoticons;

        $a_emotions[ $tmp_fid_emotion ] = $tmp_num_votes_emoticons;

      }
    }

    //hash
    $h_hash = openssl_encrypt( get_the_ID(), "AES-128-ECB", "6GaVg3.-m80hgKS" );
  
    ob_start();
    
  
    ?>
      <div class="newmoji_main">
        <div class="row">
          <div class="col-nwe-12">
            <p class="p-reaccion">¿Cuál es tu reacción?</p>
          </div>
        </div>
        <div id="cont_id_newmoji_<?php echo $h_hash; ?>" class="row cont_newmoji">
          <div class="only_nemoji off col-nwe-2" data-info="1" data-hashinfo="<?php echo $h_hash; ?>">
            <div class="row">
              <div class="col-nwe-12">
                <span><img src="<?php echo $url_newmoji; ?>/feliz.png" alt=""></span>
              </div>
            </div>
            <div class="row">
              <div class="col-nwe-12">
                <p class="p-feeling">Feliz</p>
              </div>
            </div>
            <div class="row">
              <div class="col-nwe-12">
                <p class="p-number chip info"><?php echo $a_emotions[1]; ?></p>
              </div>
            </div>
          </div>
          <div class="only_nemoji off col-nwe-2" data-info="2" data-hashinfo="<?php echo $h_hash; ?>">
            <div class="row">
              <div class="col-nwe-12">
                <span><img src="<?php echo $url_newmoji; ?>/risas.png" alt=""></span>
              </div>
            </div>
            <div class="row">
              <div class="col-nwe-12">
                <p class="p-feeling">Alegre</p>
              </div>
            </div>
            <div class="row">
              <div class="col-nwe-12">
                <p class="p-number chip info"><?php echo $a_emotions[2]; ?></p>
              </div>
            </div>
          </div>
          <div class="only_nemoji off col-nwe-2" data-info="3" data-hashinfo="<?php echo $h_hash; ?>">
            <div class="row">
              <div class="col-nwe-12">
                <span><img src="<?php echo $url_newmoji; ?>/no_me_importa.png" alt=""></span>
              </div>
            </div>
            <div class="row">
              <div class="col-nwe-12">
                <p class="p-feeling">Da igual</p>
              </div>
            </div>
            <div class="row">
              <div class="col-nwe-12">
                <p class="p-number chip info"><?php echo $a_emotions[3]; ?></p>
              </div>
            </div>
          </div>
          <div class="only_nemoji off col-nwe-2" data-info="4" data-hashinfo="<?php echo $h_hash; ?>">
            <div class="row">
              <div class="col-nwe-12">
                <span><img src="<?php echo $url_newmoji; ?>/enojo.png" alt=""></span>
              </div>
            </div>
            <div class="row">
              <div class="col-nwe-12">
                <p class="p-feeling">Enojo</p>
              </div>
            </div>
            <div class="row">
              <div class="col-nwe-12">
                <p class="p-number chip info"><?php echo $a_emotions[4]; ?></p>
              </div>
            </div>
          </div>
          <div class="only_nemoji off col-nwe-2" data-info="5" data-hashinfo="<?php echo $h_hash; ?>">
            <div class="row">
              <div class="col-nwe-12">
                <span><img src="<?php echo $url_newmoji; ?>/tristeza.png" alt=""></span>
              </div>
            </div>
            <div class="row">
              <div class="col-nwe-12">
                <p class="p-feeling">Tristeza</p>
              </div>
            </div>
            <div class="row">
              <div class="col-nwe-12">
                <p class="p-number chip info"><?php echo $a_emotions[5]; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" id="h_newmoji_hash_<?php echo $h_hash; ?>" name="h_newmoji_hash_<?php echo $h_hash; ?>" value="<?php echo $h_hash; ?>">
    <?php
  
    $html_votes = ob_get_contents();
    ob_end_clean();
  
    return $content .= $html_votes_exist . $html_votes;
  }
}



//insert styles and js
if ( !function_exists( 'nwmj_newmoji_callback_scripts' ) ) {

  function nwmj_newmoji_callback_scripts() {
  
    $url_newmoji = NWMJ_NEWMOJI__PLUGIN_URL . 'assets';

    wp_register_style( 'namespace', $url_newmoji . "/css/newmoji.css" );
    wp_enqueue_style( 'namespace' );
    wp_enqueue_script( 'namespaceformyscript', $url_newmoji . "/js/main_newmoji.js", array(), false, true );

    wp_localize_script( 
      'namespaceformyscript', 
      'localize_vars', 
      array( 
        'url' => site_url('/')
      ) 
    );

  }
}


// calls AJAX
if ( !function_exists( 'nwmj_newmoji_save_ajax' ) ) {
  function nwmj_newmoji_save_ajax(){
      global $wpdb;
  
      $path_browser_client = NWMJ_NEWMOJI__PLUGIN_DIR . "libs/NWMJBrowserClient.php";
      $path_ip_client      = NWMJ_NEWMOJI__PLUGIN_DIR . "libs/NWMJIPClient.php";

      require_once ( $path_browser_client );
      require_once ( $path_ip_client );
  
      $browser = new NWMJBrowserClient();
      $ip      = new NWMJIPClient();
  
      // Check parameters
      $action_emoji = isset( $_POST['action_emoji'] ) ? sanitize_text_field( $_POST['action_emoji'] )  : false;
      $h_hash       = isset( $_POST['h_hash'] ) ? sanitize_text_field( $_POST['h_hash'] )  : false;
      $old_hash     = $h_hash;
      $navegador    = $browser->getBrowser();
      $ips          = $ip->getIP();
      $content      = array( 
        'browser_name'       => $browser->getBrowser(),
        'browser_version'    => $browser->getVersion(),
        'browser_user_agent' => $browser->getUserAgent(),
        'platform'           => $browser->getPlatform(),
      );
  
      $content_json = json_encode( $content );
      $content_json = nwmj_newmoji_escape_MYSQL( $content_json );
      $content_txt  = implode( "|", $content );
  
  
      
      //cifrando votacion
      $fid_posts = openssl_decrypt( $h_hash, "AES-128-ECB", "6GaVg3.-m80hgKS" );
  
      $txt_hash = sprintf( "%d|%s|%s", $fid_posts, $ips, $content_txt );
  
      $h_hash = openssl_encrypt( $txt_hash, "AES-128-ECB", "6GaVg3.-m80hgKS" );
  
      //search if exist
  
      $sql_search_votes = sprintf("SELECT *
                                    FROM %s
                                    WHERE fid_posts = %d AND hash_votes = '%s'
                                    LIMIT 1;", $wpdb->prefix . 'newmoji_votes', $fid_posts, $h_hash );
  
      $prepared_query = $wpdb->prepare( $sql_search_votes );
  
      $results = $wpdb->get_results( $prepared_query );
  
  
      if($wpdb->last_error !== '') :
        $wpdb->print_error();
      endif;
  
      
      if ( !empty( $results ) ) {
        wp_send_json( 
          array(
            'message'   => __('The repeat votes', 'wpduf'),
            'status'    => 'FAIL',
            'html'      => '',
            'http_code' => 200,
            'data'      => array()
          ) 
        );
      }
      
  
      if( !$action_emoji ){
        wp_send_json(  
          array(
            'message'   => __('Data not received :(', 'wpduf'),
            'status'    => 'FAIL',
            'html'      => '',
            'http_code' => 200,
            'data'      => array()
          ) 
        );
      } else{
  
        $wpdb->insert( $wpdb->prefix . 'newmoji_votes', 
          array(
            'fid_emotion' => $action_emoji,
            'fid_posts'   => $fid_posts,
            'ip'          => $ips,
            'navegador'   => $navegador,
            'content'     => $content_txt,
            'hash_votes'  => $h_hash,
            'date_time'   => date('Y-m-d H:i:s')
          ),
          array(
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
          ) 
        ); 
  
  
        wp_send_json( 
          array(
            'message'   => __('Message received, greetings from server!', 'wpduf'),
            'status'    => 'OK',
            'html'      => '',
            'http_code' => 200,
            'data'      => array(
              'hash_code' => $old_hash
            )
          ) 
        );

      } 
  }
}


if ( !function_exists( 'nwmj_newmoji_escape_MYSQL' ) ) {
  function nwmj_newmoji_escape_MYSQL($value)
  {
      $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
      $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
  
      return str_replace($search, $replace, $value);
  }
}



//install tables
function nwmj_newmoji_installer(){

  $url_installer = plugin_dir_path(__FILE__) . "newmoji-installer.php";

  include( $url_installer );

}