<?php

/**
 * Global helpers file with misc functions.
 */
if (!function_exists('triggerAsyncRequest')) {
    function triggerAsyncRequest($url, $params = "", $method = "GET", $headers = [])
    {
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $url);
        curl_setopt($channel, CURLOPT_NOSIGNAL, 1);
        curl_setopt($channel, CURLOPT_TIMEOUT_MS, 200);
        if($method == "post" || $method == "POST") {
            curl_setopt($channel, CURLOPT_POST, true);
            curl_setopt($channel, CURLOPT_POSTFIELDS, $params);
        }
        if ($headers) {
            curl_setopt($channel, CURLOPT_HTTPHEADER, $headers);
        }
        curl_exec($channel);
        curl_close($channel);
    }
}

if (!function_exists('triggerSyncRequest')) {
    function triggerSyncRequest($url, $method = 'GET', $params = [], $headers = [])
    {
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($method != 'GET') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, true);
    }
}

if (!function_exists('getSlug')) {
    function getSlug($text, $allowUnder = false) {
        static $charMap = array(
            "à" => "a", "ả" => "a", "ã" => "a", "á" => "a", "ạ" => "a", "ă" => "a", "ằ" => "a", "ẳ" => "a", "ẵ" => "a", "ắ" => "a", "ặ" => "a", "â" => "a", "ầ" => "a", "ẩ" => "a", "ẫ" => "a", "ấ" => "a", "ậ" => "a",
            "đ" => "d",
            "è" => "e", "ẻ" => "e", "ẽ" => "e", "é" => "e", "ẹ" => "e", "ê" => "e", "ề" => "e", "ể" => "e", "ễ" => "e", "ế" => "e", "ệ" => "e",
            "ì" => "i", "ỉ" => "i", "ĩ" => "i", "í" => "i", "ị" => "i",
            "ò" => "o", "ỏ" => "o", "õ" => "o", "ó" => "o", "ọ" => "o", "ô" => "o", "ồ" => "o", "ổ" => "o", "ỗ" => "o", "ố" => "o", "ộ" => "o", "ơ" => "o", "ờ" => "o", "ở" => "o", "ỡ" => "o", "ớ" => "o", "ợ" => "o",
            "ù" => "u", "ủ" => "u", "ũ" => "u", "ú" => "u", "ụ" => "u", "ư" => "u", "ừ" => "u", "ử" => "u", "ữ" => "u", "ứ" => "u", "ự" => "u",
            "ỳ" => "y", "ỷ" => "y", "ỹ" => "y", "ý" => "y", "ỵ" => "y",
            "À" => "A", "Ả" => "A", "Ã" => "A", "Á" => "A", "Ạ" => "A", "Ă" => "A", "Ằ" => "A", "Ẳ" => "A", "Ẵ" => "A", "Ắ" => "A", "Ặ" => "A", "Â" => "A", "Ầ" => "A", "Ẩ" => "A", "Ẫ" => "A", "Ấ" => "A", "Ậ" => "A",
            "Đ" => "D",
            "È" => "E", "Ẻ" => "E", "Ẽ" => "E", "É" => "E", "Ẹ" => "E", "Ê" => "E", "Ề" => "E", "Ể" => "E", "Ễ" => "E", "Ế" => "E", "Ệ" => "E",
            "Ì" => "I", "Ỉ" => "I", "Ĩ" => "I", "Í" => "I", "Ị" => "I",
            "Ò" => "O", "Ỏ" => "O", "Õ" => "O", "Ó" => "O", "Ọ" => "O", "Ô" => "O", "Ồ" => "O", "Ổ" => "O", "Ỗ" => "O", "Ố" => "O", "Ộ" => "O", "Ơ" => "O", "Ờ" => "O", "Ở" => "O", "Ỡ" => "O", "Ớ" => "O", "Ợ" => "O",
            "Ù" => "U", "Ủ" => "U", "Ũ" => "U", "Ú" => "U", "Ụ" => "U", "Ư" => "U", "Ừ" => "U", "Ử" => "U", "Ữ" => "U", "Ứ" => "U", "Ự" => "U",
            "Ỳ" => "Y", "Ỷ" => "Y", "Ỹ" => "Y", "Ý" => "Y", "Ỵ" => "Y"
        );
        $text = strtr($text, $charMap);
        $regExpression = "`\W`i";

        if ($allowUnder)
            $regExpression = "`[^a-zA-Z0-9-]`i";

        $text = preg_replace(array($regExpression, "`[-]+`",), "-", $text);
        $text =  trim($text, "-");

        return strtolower($text);
    }
}
