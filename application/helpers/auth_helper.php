<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function checkLoggedIn() {
    $CI =& get_instance();
    if (!isset($CI->session->userName)) {
        redirect('Home');
    }
}
