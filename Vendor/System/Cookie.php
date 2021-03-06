<?php

namespace System;

class Cookie
{

    /**
     * Application object
     *
     * @var \System\App
     */
    private $app;

    /**
     * Cookies Path
     *
     * @var string
     */
    private $path = '/';

    /**
     * Constructor
     *
     * @param \System\App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;

        // we will get the path from SCRIPT_NAME index from $_SERVER variable
        // we will remove or just get the directory of the script name by removing
        // the file name from it => we will remove index.php
        $this->path = dirname($this->app->request->server('SCRIPT_NAME')) ?: '/';
    }

    /**
     * Set a new value to cookie
     *
     * @param string $key
     * @param mixed $value
     * @param int $durationInDays
     * @return void
     */
    public function set($key, $value, $durationInDays = 30)
    {
        // here we will see if the hours variable equals -1
        // then it means we will remove the key from cookies
        // otherwise, we will just add our normal time
        $expireTime = $durationInDays == -1 ? -1 : time() + $durationInDays * 3600 * 24;
        setcookie($key, $value, $expireTime, $this->path, '', FALSE, FALSE);
    }

    /**
     * Get value from cookie by the given key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = NULL)
    {
        return array_get($_COOKIE, $key, $default);
    }

    /**
     * Determine if the cookie has the given key
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $_COOKIE);
    }

    /**
     * Remove the given key from cookie
     *
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        $this->set($key, NULL, -1);
        unset($_COOKIE[$key]);
    }

    /**
     * Get all cookies data
     *
     * @return array
     */
    public function all()
    {
        return $_COOKIE;
    }

    /**
     * Destroy cookie
     *
     * @return void
     */
    public function destroy()
    {
        foreach (array_keys($this->all()) as $key) {
            $this->remove($key);
        }
        unset($_COOKIE);
    }

}
