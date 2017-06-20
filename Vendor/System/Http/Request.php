<?php
namespace System\Http;
use System\App;

class Request {
    /**
     * URL
     * 
     * @var string
     */
    private $url;
    
    /**
     * Clean URL
     * 
     * @var string
     */
    private $baseURL;
    
    /**
     * Uploaded files container
     * 
     * @var array
     */
    private $files  =   [];

    /**
     * Prepare URL
     * 
     * @return void
     */
    public function prepareUrl()
    {
        $script     =   preg_replace('~/index.php~i', '', $this->server('SCRIPT_NAME'), 1);
        $requestURI =   $this->server('REQUEST_URI');
        if (strpos($requestURI, '?') !== FALSE)
        {
            list($requestURI, $queryString) = explode('?', $requestURI);
        }
        $this->url      = rtrim(preg_replace('~^' . $script . '~', '', $requestURI), '/');
        if (! $this->url){
            $this->url  =   '/';
        }
        $this->baseURL  = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $script . '/';
    }
    
    /**
     * Get value from $_GET with the given key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key,$default = NULL)
    {
        return array_get($_GET, $key, $default);
    }
    
    /**
     * Get value from $_POST with the given key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function post($key, $default = NULL)
    {
        return array_get($_POST, $key, $default);
    }
    
    /**
     * Get the uploaded file object for the given input
     * 
     * @param string $input
     * @return \System\Http\UploadedFiles
     */
    public function file($input)
    {
        if (isset($this->files[$input])){
            return $this->files[$input];
        }
        $uploadedFile   =   new UploadedFiles($input);
        $this->files[$input]    =   $uploadedFile;
        return $this->files[$input];
    }

        /**
     * Get value from $_SERVER with the given key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function server ($key, $default = NULL)
    {
        return array_get($_SERVER, $key, $default);
    }
    
    /**
     * Get current request method
     * 
     * @return string
     */
    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }
    
    /**
     * Get full URL of the script
     * 
     * @return string
     */
    public function baseUrl()
    {
        return $this->baseURL;
    }
    
    /**
     * Get only relative URL (clean URL)
     * 
     * @return string
     */
    public function url()
    {
        return $this->url;
    }
}










