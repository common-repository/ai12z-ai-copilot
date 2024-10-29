<?php
// Custom logging function
function ai12z_log($message) {
    if (WP_DEBUG === true) {
        error_log("[ai12z_log] " . $message);
    }
}
