<?php
  /**
   * Plugin Name: Church Branches and Programs Generator
   * Description: Create, edit, and manage pages for each branch location. Configure events and programs for each branch, with ease. Created for the Foundation of the Rock Church.
   * Version: 1.1.0
   * Author: David Cai, add your names here
   * License: GPL-2.0+
   * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
   * Text Domain: church-branches-generator
   * Domain Path: /languages
   */
  
  // If this file is called directly, abort.
  if (!defined('WPINC')) {
      die;
  }
  
  define('CHURCH_BRANCHES_GENERATOR_VERSION', '1.0.0');
  define('CHURCH_BRANCHES_GENERATOR_PLUGIN_DIR', plugin_dir_path(__FILE__));
  define('CHURCH_BRANCHES_GENERATOR_PLUGIN_URL', plugin_dir_url(__FILE__));
  
  
  function activate_church_branches_generator() {
      require_once CHURCH_BRANCHES_GENERATOR_PLUGIN_DIR . 'includes/class-activator.php';
      Church_Branches_Generator_Activator::activate();
  }
  register_activation_hook(__FILE__, 'activate_church_branches_generator');
  
  
  
  function deactivate_church_branches_generator() {
      require_once CHURCH_BRANCHES_GENERATOR_PLUGIN_DIR . 'includes/class-deactivator.php';
      Church_Branches_Generator_Deactivator::deactivate();
  }
  register_deactivation_hook(__FILE__, 'deactivate_church_branches_generator');
  
  
  require CHURCH_BRANCHES_GENERATOR_PLUGIN_DIR . 'includes/class-plugin.php';
  
  function run_church_branches_generator() {
      $plugin = new Church_Branches_Generator_Plugin();
      $plugin->run();
  }
  run_church_branches_generator();
  

  class Branch_Creator {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
    }

    // Add the menu item to the dashboard
    public function add_admin_menu() {
        add_menu_page(
            'Create New Branch',  // Page Title
            'Branch Creator',     // Menu Title
            'manage_options',     // Capability
            'branch-creator',     // Menu Slug
            array( $this, 'render_admin_page' ), // Callback function
            'dashicons-admin-page', // Icon
            100 // Position
        );
    }

    // Render the Admin Page Form
    public function render_admin_page() {
        // Check if the form was submitted
        if ( isset( $_POST['bc_submit_branch'] ) && isset( $_POST['bc_nonce_field'] ) ) {
            // Verify Nonce for security
            if ( wp_verify_nonce( $_POST['bc_nonce_field'], 'bc_create_branch_nonce' ) ) {
                $this->process_branch_creation();
            } else {
                echo '<div class="notice notice-error"><p>Security check failed. Try again later.</p></div>';
            }
        }

        ?>

        <div class="wrap">
            <h1>Create New Branch Page</h1>
            <p>Fill out the details below to generate a new page for a branch location.</p>
            
            <form method="post" action="">
                <?php wp_nonce_field( 'bc_create_branch_nonce', 'bc_nonce_field' ); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="branch_name">Branch Name <italic>(Branch name only, do not add State Branch)</italic><b>(e.g. Lagos)</b></label></th>
                        <td><input name="branch_name" type="text" label id="branch_name" value="" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="address">Address (e.g. 123 Lagos Street)</label></th>
                        <td><input name="address" type="text" id="address" value="" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="phone">Phone (e.g. 123-456-7890)</label></th>
                        <td><input name="phone" type="text" id="phone" value="" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="email">Email (e.g. example@email.com)</label></th>
                        <td><input name="email" type="email" id="email" value="" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="service_times">Service Times</label></th>
                        <td><input name="service_times" type="text" id="service_times" value="" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="lead_pastor">Lead Pastor</label></th>
                        <td><input name="lead_pastor" type="text" id="lead_pastor" value="" class="regular-text" required></td>
                    </tr>
                </table>
                
                <?php submit_button( 'Create Branch Page', 'primary', 'bc_submit_branch' ); ?>
            </form>
        </div>
        <?php
    }

    // Process the form and create the page
    private function process_branch_creation() {
        // Sanitize Inputs
        $branch_name   = sanitize_text_field( $_POST['branch_name'] );
        $address       = sanitize_text_field( $_POST['address'] );
        $phone         = sanitize_text_field( $_POST['phone'] );
        $email         = sanitize_email( $_POST['email'] );
        $service_times = sanitize_text_field( $_POST['service_times'] );
        $lead_pastor   = sanitize_text_field( $_POST['lead_pastor'] );

        // 1. Define the HTML Template

        // Image Variables
        $location_img = plugin_dir_url(__FILE__) . 'public/images/ion_location-outline.png';
        $phone_img = plugin_dir_url(__FILE__) . 'public/images/mingcute_phone-fill.png';



        $page_content = <<<HTML

        <main class="branch-page-wrapper">
      <section class="branch-hero" role="banner">
        <div class="hero-content">
          <h1>{$branch_name} State Branch</h1>
          <p>
            The {$branch_name} State branch is a vibrant community of believers
            committed to spreading the gospel and serving the community with
            love and compassion.
          </p>
        </div>
      </section>

      <section class="branch-content">
        <div class="branch-grid">
          <article class="branch-card" aria-label="Contact Information">
            <div class="branch-info">
              <h2 class="section-title">Contact Information</h2>
              <div class="info-list">
                <div class="info-item">
                  <div class="info-item-icon"><img src="{$location_img}" alt="Location icon" /></div>
                  <div>
                    <h4>Address</h4>
                    <p>{$address}</p>
                  </div>
                </div>
                <div class="info-item">
                  <div class="info-item-icon"><img src="{$phone_img}" alt="Phone icon" /></div>
                  <div>
                    <h4>Phone</h4>
                    <p><a href="tel:{$phone}">{$phone}</a></p>
                  </div>
                </div>
                <div class="info-item">
                  <div class="info-item-icon"><img src="{$location_img}" alt="Location icon" /></div>
                  <div>
                    <h4>Email</h4>
                    <p><a href="mailto:{$email}">{$email}</a></p>
                  </div>
                </div>
                <div class="info-item">
                  <div class="info-item-icon"><img src="{$location_img}" alt="Location icon" /></div>
                  <div>
                    <h4>Service Times</h4>
                    <p>{$service_times}</p>
                  </div>
                </div>
                <div class="info-item">
                  <div class="info-item-icon"><img src="{$location_img}" alt="Location icon" /></div>
                  <div>
                    <h4>Lead Pastor</h4>
                    <p>{$lead_pastor}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="get-directions-btn">
              <a href="#" class="btn btn-primary">Get Directions</a>
            </div>
          </article>

          <article class="branch-card" aria-label="About and Services">
            <h2 class="section-title">About Our Branch</h2>
            <p class="about-text">
              Welcome to {$branch_name} State Branch! We are a vibrant community
              of believers dedicated to worship, fellowship, and service. Our
              church is committed to creating an environment where everyone can
              experience the love of God and grow in their faith.
            </p>
            <p class="about-text">
              Whether you're new to the area, searching for a church home, or
              simply exploring faith, we would love to meet you. Join us for our
              Sunday services and experience the warmth of our community.
            </p>

            <div class="services-card">
              <h3>Weekly Services &amp; Activities</h3>
              <div class="service-row">
                <h4>Sunday Worship Service</h4>
                <p>Main service with inspiring worship and teaching</p>
              </div>
              <div class="service-row">
                <h4>Tuesday Bible Study</h4>
                <p>Deep dive into God's Word – 6:00 PM</p>
              </div>
              <div class="service-row">
                <h4>Thursday Prayer Meeting</h4>
                <p>Intercessory prayer and worship – 5:30 PM</p>
              </div>
              <div class="service-row">
                <h4>Youth Fellowship</h4>
                <p>Every Saturday – 4:00 PM</p>
              </div>
            </div>
          </article>
        </div>
        </section>
      
      
      <section class="cta-section" aria-label="Visit Us This Sunday">
          <h2>Visit Us This Sunday</h2>
          <p>We can't wait to welcome you into our church family!</p>
          <div class="cta-buttons">
            <a href="#" class="btn btn-primary">View all Churches</a>
            <a href="#" class="btn btn-outline">Get Directions</a>
          </div>
		</section>
    </main>
HTML;

        // 2. Setup Page Arguments
        // We check if a parent page with slug 'website' exists.
        $parent_page = get_page_by_path( 'website' );
        $parent_id = $parent_page ? $parent_page->ID : 0;

        $page_data = array(
            'post_title'    => $branch_name, // The Title input
            'post_content'  => $page_content, // The HTML content
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_author'   => get_current_user_id(),
            'post_parent'   => $parent_id, // Tries to set parent to /website/
            'post_name'     => sanitize_title( $branch_name ) . '-branch' // Creates the slug
        );

        // 3. Insert the Page
        $page_id = wp_insert_post( $page_data );

        if ( ! is_wp_error( $page_id ) ) {
            // Success Message
            // We construct the URL to show exactly where it was created
            $link = get_permalink( $page_id );
            echo '<div class="notice notice-success is-dismissible">';
            echo "<p>Page created successfully! <a href='{$link}' target='_blank'>View Page</a></p>";
            echo '</div>';
        } else {
            echo '<div class="notice notice-error"><p>There was an error creating the page. Please try again.</p></div>';
        }
    }
}

// Initialize the Plugin
new Branch_Creator();

