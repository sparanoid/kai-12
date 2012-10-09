<?php
/**
 * Twenty Twelve Kai functions and definitions
 */

function add_twentytwelve_credits() {
  echo 'Theme: <a href="http://postholic.com/rsrc/twentytwelve-kai/">Twenty Twelve Kai</a><span class="sep">.</span> ';
}

add_action('twentytwelve_credits', 'add_twentytwelve_credits');
?>