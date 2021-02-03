<?php 
namespace appforge\coreex\includes\models;

class Request
{
    private $server;
    public $get;
    public $post;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;
    }

    public function get($name)
    {
        return $this->get[$name];
    }

    public function post($name)
    {
        if(array_key_exists($name, $this->post))
            return $this->post[$name];
        return null;
    }

    public function getIsGet()
    {
        return $this->server['REQUEST_METHOD'] == 'GET';
    }

    public function getIsPost()
    {
        return $this->server['REQUEST_METHOD'] == 'POST';
    }

    public function getReferrer()
    {
        return $this->server['HTTP_REFERER'];
    }

    public function getIp()
    {
        return $this->server['REMOTE_ADDR'];
    }

    public function getUri()
    {
        return $this->server['REQUEST_URI'];
    }

    public function getHost()
    {
        return $this->server['HTTP_HOST'];
    }

    /**
     * http or https
     */
    public function getRequestScheme()
    {
        return $this->server['REQUEST_SCHEME'];
    }

    public function getOrigin()
    {
        return $this->server['HTTP_ORIGIN'];
    }

    public function getCookies()
    {
        $cookiesTmp = $this->server['HTTP_COOKIE'];
        $cookiesTmp = explode('; ', $cookiesTmp);
        $cookies = [];
        foreach($cookiesTmp as $tmp)
        {
            $parts = explode('=',$tmp);
            $cookies[$parts[0]] = $parts[1];
        }
        return $cookies;
    }
    
    
}