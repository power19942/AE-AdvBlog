<?php

namespace System;

class Loader
{

    /**
     * Application object
     * 
     * @var \System\App
     */
    private $app;

    /**
     * Controllers container
     * 
     * @var array
     */
    private $controllers = [];

    /**
     * Models container
     * 
     * @var array
     */
    private $models = [];

    /**
     * Constructor
     * 
     * @param \System\App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Call the given controller with the given method
     * and pass the given arguments to the controller method
     * 
     * @param string $controller
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function action($controller, $method, array $arguments = [])
    {
        $object = $this->controller($controller);
        return call_user_func_array([$object, $method], $arguments);
    }

    /**
     * Call the given controller
     * 
     * @param string $controller
     * @return object
     */
    public function controller($controller)
    {
        $controller = $this->getControllerName($controller);
        if (!$this->hasController($controller)) {
            $this->addController($controller);
        }
        return $this->getController($controller);
    }

    /**
     * Determine if the given class/controller exists in controllers container
     * 
     * @param string $controller
     * @return bool
     */
    private function hasController($controller)
    {
        return array_key_exists($controller, $this->controllers);
    }

    /**
     * Create a new object for the given controller and store it in controllers container
     * 
     * @param string $controller
     * @return void
     */
    private function addController($controller)
    {
        $object                         = new $controller($this->app);
        $this->controllers[$controller] = $object;
    }

    /**
     * Get the controller object
     * 
     * @param string $controller
     * @return object
     */
    private function getController($controller)
    {
        return $this->controllers[$controller];
    }

    /**
     * Get the full class name for the given controller
     * 
     * @param string $controller
     * @return string
     */
    private function getControllerName($controller)
    {
        $controller .= 'Controller';
        $controller = 'App\\Controllers\\' . $controller;
        return str_replace('/', '\\', $controller);
    }

    /**
     * Call the given model
     * 
     * @param string $model
     * @return object
     */
    public function model($model)
    {
        $model = $this->getModelName($model);
        if (!$this->hasModel($model)) {
            $this->addModel($model);
        }
        return $this->getModel($model);
    }

    /**
     * Determine if the given class/model exists in models container
     * 
     * @param string $model
     * @return bool
     */
    private function hasModel($model)
    {
        return array_key_exists($model, $this->models);
    }

    /**
     * Create a new object for the given model and store it in models container
     * 
     * @param string $model
     * @return void
     */
    private function addModel($model)
    {
        $object               = new $model($this->app);
        $this->models[$model] = $object;
    }

    /**
     * Get the model object
     * 
     * @param string $model
     * @return object
     */
    private function getModel($model)
    {
        return $this->models[$model];
    }

    /**
     * Get the full class name for the given model
     * 
     * @param string $model
     * @return string
     */
    private function getModelName($model)
    {
        $model .= 'Model';
        $model = 'App\\Models\\' . $model;
        return str_replace('/', '\\', $model);
    }

}
