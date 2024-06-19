<?php

if (! function_exists('hara_add_language_to_menu_storage_key')) {
    function hara_add_language_to_menu_storage_key( $storage_key )
    {
      global $sitepress;

      return $storage_key . '-' . $sitepress->get_current_language();
    }
}
add_filter( 'hara_menu_storage_key', 'hara_add_language_to_menu_storage_key', 10, 1 );