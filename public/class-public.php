<?php
  class Church_Branches_Generator_Public {
      private $plugin_name;
      private $version;
  
      public function __construct($plugin_name, $version) {
          $this->plugin_name = $plugin_name;
          $this->version = $version;
      }
  
      public function enqueue_styles() {
          wp_enqueue_style($this->plugin_name . '-reset', CHURCH_BRANCHES_GENERATOR_PLUGIN_URL . 'css/reset.css', array(), $this->version, 'all');
          wp_enqueue_style($this->plugin_name . '-elementor', CHURCH_BRANCHES_GENERATOR_PLUGIN_URL . 'css/elementor-frontend-inline.css', array($this->plugin_name . '-reset'), $this->version, 'all');
          wp_enqueue_style($this->plugin_name . '-frontend', CHURCH_BRANCHES_GENERATOR_PLUGIN_URL . 'css/frontend.min.css', array($this->plugin_name . '-elementor'), $this->version, 'all');
          wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/public-style.css', array($this->plugin_name . '-frontend'), $this->version, 'all');
      }
  
      public function enqueue_scripts() {
          wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/public-script.js', array('jquery'), $this->version, false);
      }
  }
  