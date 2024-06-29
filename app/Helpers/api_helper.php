<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function json_encode($data) {
    if (function_exists('json_encode')) {
        return json_encode($data);
    } else {
        // Fallback for PHP versions < 5.2
        require_once 'json.php';
        $json = new Services_JSON();
        return $json->encode($data);
    }
}

function json_decode($data, $assoc = false) {
    if (function_exists('json_decode')) {
        return json_decode($data, $assoc);
    } else {
        // Fallback for PHP versions < 5.2
        require_once 'json.php';
        $json = new Services_JSON($assoc ? SERVICES_JSON_LOOSE_TYPE : 0);
        return $json->decode($data);
    }
}
