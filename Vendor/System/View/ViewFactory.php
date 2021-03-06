<?php

namespace System\View;

use System\App;

class ViewFactory
{

    /**
     * Application object
     * 
     * @var \System\App
     */
    private $app;

    /**
     * Constructor
     * 
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Render the given view path with the passed variables and generate new
     * 
     * @param string $viewPath
     * @param array $data
     * @return \System\View\ViewInterface
     */
    public function render($viewPath, array $data = [])
    {
        return new View($this->app->file, $viewPath, $data);
    }

}
