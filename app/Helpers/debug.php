<?php

if (!function_exists('ddd')) {
    function ddd($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

if (!function_exists('cust_dd')) {
    function cust_dd($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
    }
}
