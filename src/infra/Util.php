<?php
class Util {
 
    public static function getUrlQuery() {
        $query_string = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $query_array = array();
        parse_str($query_string, $query_array);
        return $query_array;
    }

    public static function redirectPageTo($path, $data = []) {
        $queryParam = "";
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
         
        if (count($data) > 0) {
            $queryParam = "?".http_build_query($data);
        }
        
        header("location: $protocol://$host$path$queryParam");
    }

    public static function verifyEmptyIsset($value) {
        return isset($value) && !empty($value) ;
    }
}

?>