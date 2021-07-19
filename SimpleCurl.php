<?php

/**
 * Class SimpleCurl
 * @url https://bitbucket.org/engrsayedahmd/simplecurl/src/master/
 */
class SimpleCurl
{
    protected static $url;
    protected static $headers;
    protected static $query;
    protected static $response;

    /**
     * @param $url
     * @param array $headers
     * @param $query
     */
    public static function prepare($url, $query, array $headers = [])
    {
        self::$url = $url;
        self::$headers = $headers;
        self::$query = http_build_query($query);
    }

    /**
     *  Execute post method curl request
     */
    public static function post()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::$headers);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, self::$query);
        self::$response = curl_exec($curl);
        curl_close($curl);
    }

    /**
     *  Execute get method curl request
     */
    public static function get()
    {
        $url = self::$url . '?' . self::$query;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::$headers);
        self::$response = curl_exec($curl);
        curl_close($curl);
    }

    /**
     * @return mixed
     */
    public static function getResponse()
    {
        return self::$response;
    }

    /**
     * @return mixed
     */
    public static function getResponseAssoc()
    {
        return json_decode(self::$response, true);
    }
}