<?php
use Phalcon\Di;

if (!function_exists('t')) {
    /**
     * Translation function call anywhere.
     * Returns the translation string of the given key.
     *
     * @param  string $string       The string to be translated
     * @param  array  $placeholders The placeholders
     * @return string
     */
    function t($string, array $placeholders = null)
    {
        $di = Di::getDefault();
        if ($di && $di->has('translation')) {
            /** @var \Phalcon\Translate\Adapter $translation */
            $translation = $di->getShared('translation');

            return $translation->t($string, $placeholders);
        }

        return $string;
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the Application path.
     *
     * @param  string $path
     * @return string
     */
    function app_path($path = '')
    {
        return BASE_PATH. ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if (!function_exists('public_path')) {
    /**
     * Get the Public Application path.
     *
     * @param  string $path
     * @return string
     */
    function public_path($path = '')
    {
        return app_path('public') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app_path('app' . DIRECTORY_SEPARATOR . 'config') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'empty':
                return '';
            case 'null':
                return null;
        }

        return $value;
    }
}

if (!function_exists('container')) {
    /**
     * Calls the default Dependency Injection container.
     *
     * @param  mixed
     * @return mixed|\Phalcon\DiInterface
     */
    function container()
    {
        $default = Di::getDefault();
        $args = func_get_args();

        if (empty($args)) {
            return $default;
        }

        return call_user_func_array([$default, 'get'], $args);
    }
}
