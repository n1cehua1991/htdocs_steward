<?php
namespace App\Services;

class HttpService {

    /**
     *
     * @param $url
     * @param null $http_opts
     * @param bool $is_transcoding
     * @return array
     */
    public static function get( $url, $http_opts = null, $is_transcoding = true ) {

        if ( !isset( $url ) || empty( $url ) ) {
            return [ 'code' => 400, 'message' => '缺少请求链接' ];
        }

        $curl_handler = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_HEADER => false,
            CURLOPT_USERAGENT  => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.114 Safari/537.36',
        ];

        if (is_array($http_opts)) {
            foreach ($http_opts as $key => $value){
                $options[$key] = $value;
            }
        }

        curl_setopt_array($curl_handler, $options);
        $curl_result = curl_exec($curl_handler);
        $curl_http_status = curl_getinfo($curl_handler,CURLINFO_HTTP_CODE);
        $curl_http_info = curl_getinfo($curl_handler);
        if ($curl_result === false) {
            $error = curl_error($curl_handler);
            curl_close($curl_handler);
            return [ 'code' => $curl_http_status, 'message' => $error,'http_info' => $curl_http_info ];
        }
        if ($is_transcoding) {
            $encode = mb_detect_encoding($curl_result, [ 'ASCII', 'UTF-8','GB2312', 'GBK', 'BIG5' ]);
            if ($encode != 'UTF-8') {
                $curl_result = iconv($encode, 'UTF-8', $curl_result);
            }
        }

        $result = json_decode($curl_result,true);
        if (is_null($result) || empty($result)) {
            $result = $curl_result;
        }

        curl_close($curl_handler);

        return [ 'code' => $curl_http_status, 'data' => $result,'http_info' => $curl_http_info ];

    }

    /**
     * post请求
     * @param string $url
     * @param array $data
     * @param array $http_opts
     * @return array
     */
    public static function post( $url, $data, $http_opts = [] ) {

        if (!isset($url) || empty($url)) {
            return ['code' => 400, 'message' => '缺少请求链接'];
        }
        if (!isset($data)) {
            return ['code' => 400 ,'message' => '缺少请求参数'];
        }

        $curl_handler = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_HEADER	 => false,
            CURLOPT_USERAGENT => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.114 Safari/537.36',
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $data
        ];

        if (!empty($http_opts)) {
            foreach ($http_opts as $key => $value) {
                $options[$key] = $value;
            }
        }

        curl_setopt_array($curl_handler, $options);
        $curl_result = curl_exec($curl_handler);
        $curl_http_status = curl_getinfo($curl_handler, CURLINFO_HTTP_CODE);
        $curl_http_info = curl_getinfo($curl_handler);

        if ($curl_result == false) {
            $error = curl_error($curl_handler);
            curl_close($curl_handler);
            return ['code' => $curl_http_status, 'message' => $error,'http_info' => $curl_http_info];
        }
        
        curl_close($curl_handler);

        $encode = mb_detect_encoding($curl_result, array('ASCII', 'UTF-8','GB2312', 'GBK', 'BIG5')); //进行编码识别
        if ($encode != 'UTF-8') {
            $curl_result = iconv($encode, 'UTF-8', $curl_result);
        }

        $result = json_decode($curl_result, true);

        if (is_null($result)) {
            $result = $curl_result;
        }
        
        return [ 'code' => 200, 'message' => 'ok', 'data' => $result ];

    }


    /**
     * @param $url
     * @param $data
     * @param null $http_opts
     * @return array
     */
    public static function put( $url, $data, $http_opts = null ) {

        if (!isset($url) || empty($url)) {
            return array( 'code' => 400, 'message' => '缺少请求链接' );
        }
        if (!isset($data) || empty($data)) {
            return array( 'code' => 400 ,'message' => '缺少请求参数');
        }
        if (is_array($data)) {
            $data = http_build_query($data);
        }

        $curl_handler = curl_init();

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_HEADER => false,
            CURLOPT_USERAGENT => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.114 Safari/537.36',
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_HTTPHEADER => array('Content-Length: ' . strlen($data))
        );

        if (is_array($http_opts)) {
            foreach ($http_opts as $key => $value){
                $options[$key] = $value;
            }
        }

        curl_setopt_array($curl_handler, $options);
        $curl_result = curl_exec($curl_handler);
        $curl_http_status = curl_getinfo($curl_handler,CURLINFO_HTTP_CODE);
        $curl_http_info = curl_getinfo($curl_handler);
        if ($curl_result == false) {
            $error = curl_error($curl_handler);
            curl_close($curl_handler);
            return array('code' => $curl_http_status, 'message' => $error,'http_info' => $curl_http_info);
        }
        curl_close($curl_handler);

        $encode = mb_detect_encoding($curl_result, array('ASCII', 'UTF-8','GB2312', 'GBK', 'BIG5'));
        if ($encode != 'UTF-8') {
            $curl_result = iconv($encode, 'UTF-8', $curl_result);
        }

        $result = json_decode($curl_result, true);
        if (is_null($result)) {
            $result = $curl_result;
        }

        return array('code' => 200, 'message' => 'ok', 'data' => $result,'http_info' => $curl_http_info);

    }

    /**
     * @param $url
     * @param null $opt
     * @return array
     */
    public static function delete($url, $opt = null) {
        if (!isset($url) || empty($url)) {
            return ['code' => 400, 'message' => '缺少请求链接'];
        }
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_FILETIME, true);
        curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, false);


        curl_setopt($curl_handle, CURLOPT_HEADER, true);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_NOSIGNAL, true);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $options = [];
        if (is_array($opt)) {
            foreach ($opt as $key => $value) {
                $options[$key] = $value;
            }
        }
        curl_setopt_array($curl_handle, $options);

        $curl_result = curl_exec ( $curl_handle );
        $curl_http_status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        $curl_http_info = curl_getinfo($curl_handle);
        if ($curl_result === false) {
            $error = curl_error($curl_handle);
            curl_close($curl_handle);
            return array('code' => $curl_http_status, 'message' => $error,'http_info' => $curl_http_info);
        }
        $encode = mb_detect_encoding($curl_result, ['ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5']);
        if ($encode != 'UTF-8') {
            $curl_result = iconv($encode, 'UTF-8', $curl_result);
        }
        $result = json_decode($curl_result, true);
        if (is_null($result) || empty($result)) {
            $result = $curl_result;
        }
        curl_close($curl_handle);
        return ['code' => $curl_http_status, 'data' => $result,'http_info' => $curl_http_info];
    }

}