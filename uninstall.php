<?php
  if (!defined('WP_UNINSTALL_PLUGIN')) {
      die;
  }
  
  // Uninstall logic here
  // For example, you might want to delete plugin options:
  delete_option('church_branches_generator_option');
  
  // Or if you have created any database tables, you might want to drop them here
  global $wpdb;
  $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}church_branches_generator_table_name");
  