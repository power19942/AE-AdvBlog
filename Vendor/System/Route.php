<?php
namespace System;

class Route
{
    /**
     * Application object
     * 
     * @var \System\App
     */
    private $app;
    
    /**
     * Routes container
     * 
     * @var array
     */
    public $routes =   [];
    
    /**
     * not found URL
     * 
     * @var string
     */
    private $notFound;
    
    /**
     * Constructor
     * 
     * @param \System\App $app
     */
    public function __construct(App $app) {
        $this->app  =   $app;
    }
    
    /**
     * Add new route
     * 
     * @param string $url
     * @param string $action
     * @param string $requestMethod
     * @return void
     */
    public function add($url, $action, $requestMethod = 'GET')
    {
        $route  =   [
            'url'       =>  $url,
            'pattern'   =>  $this->generatePattern($url),
            'action'    =>  $this->getAction($action),
            'method'    =>  strtoupper($requestMethod),
        ];
        $this->routes[]   =   $route;
    }
    
    /**
     * Set not found URL
     * 
     * @param type $url
     * @return void
     */
    public function notFound($url)
    {
        $this->notFound =   $url;
    }
    
    /**
     * Get proper route
     * 
     * @return array
     */
    public function getProperRoute()
    {
        foreach ($this->routes as $route)
        {
            if ($this->isMatching($route['pattern']))
            {
                $arguments  = $this->getArgumentsFrom($route['pattern']);
                
                // Cotroller@method
                list($controller, $method)  =   explode('@', $route['action']);
                return [$controller, $method, $arguments];
            } 
        }
        return $this->app->url->redirect($this->notFound);
    }
    
    /**
     * Determine if the given pattern matches the current request URL
     * 
     * @param string $pattern
     * @return bool
     */
    private function isMatching($pattern)
    {
        return preg_match($pattern, $this->app->request->url());
    }
    
    /**
     * Get Arguments from the current request URL based on the given pattern
     * 
     * @param string $pattern
     * @return array
     */
    private function getArgumentsFrom($pattern)
    {
        preg_match($pattern, $this->app->request->url(),$matches);
        array_shift($matches);
        return $matches;
    }
    
    /**
     * Generate a RegEx pattern for the given URL
     * 
     * @param string $url
     * @return string
     */
    private function generatePattern($url)
    {
        $pattern    =   '#^';
        $pattern    .=  str_replace([':text', ':id'], ['([a-zA-Z0-9-]+)', '(\d+)'], $url);
        $pattern    .=  '$#';
        return $pattern;
    }
    
    /**
     * Get the proper action
     * 
     * @param string $action
     * @return string
     */
    private function getAction($action)
    {
        $action = str_replace('/', '\\', $action);
        return strpos($action, '@') !== FALSE ? $action : $action . '@index';
    }
}













