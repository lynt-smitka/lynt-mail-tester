<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Lynt Mail Tester
 * Plugin URI:        https://github.com/lynt-smitka/lynt-mail-tester
 * Description:       Extremly simple plugin which allows you to send an e-mail via wp_mail function.
 * Version:           1.0.0
 * Author:            Vladimir Smitka
 * Author URI:        https://lynt.cz/
 * License:           GPL v2 or later
 * 
 */

if (!defined('ABSPATH')) {
  exit;
}

function lynt_mail_tester_add_admin_menu() {
  
  add_management_page('Lynt Mail Tester', 'Mail Tester', 'manage_options', 'lynt_mail_tester', 'lynt_mail_tester_page');
  
}

function lynt_mail_tester_page() {

  $send_ok = false;
  
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!wp_verify_nonce($_POST['_wpnonce'], 'lynt_mail_test')) {
      die('Nonce check failed');
    }
    
    $mail_to      = sanitize_email($_POST["to"]);
    $mail_subject = sanitize_text_field($_POST["subject"]);
    $mail_text    = sanitize_text_field($_POST["text"]);
    
    $send_ok = wp_mail($mail_to, $mail_subject, $mail_text);
  }
  
  if ($send_ok === true) {
?>
   <div class="notice notice-success is-dismissible">
        <p><?php _e( 'OK: The mail has been sent successfully.' , 'lynt-mail-tester' ); ?></p>
    </div> 
<?php
  }
?>
  <h2>Lynt Mail Tester</h2>
  <p><?php _e( 'Debug your mail with <a href="https://www.mail-tester.com/">mail-tester.com</a> or simlar tools.' , 'lynt-mail-tester' ); ?></p>
  <form action="<?php  echo admin_url('admin.php?page=lynt_mail_tester'); ?>" method="post">
  <?php
    wp_nonce_field('lynt_mail_test');
  ?>
    <h3><?php _e( 'To:' , 'lynt-mail-tester' ); ?></h3>
    <p><input type="email" name="to" class="regular-text" value=""/></p>
    <h3><?php _e( 'Subject:' , 'lynt-mail-tester' ); ?></h3>
    <p><input type="text" name="subject" class="regular-text" value="TEST"/></p>    
    <h3><?php _e( 'Text:' , 'lynt-mail-tester' ); ?></h3>
    <p><textarea name="text" class="regular-text"  rows="5"><?php _e( 'This is a testing e-mail, please ingnore it.' , 'lynt-mail-tester' ); ?></textarea></p>    
    <p><button type="submit" class="button button-primary"><?php _e( 'Send' , 'lynt-mail-tester' ); ?></button></p>
  </form>
<?php
}

function lynt_mail_tester_init(  ) {

  load_plugin_textdomain( 'lynt-mail-tester', false, basename( dirname( __FILE__ ) ) .'/languages' );
  
}
 

add_action('admin_menu', 'lynt_mail_tester_add_admin_menu');
add_action('plugins_loaded', 'lynt_mail_tester_init');