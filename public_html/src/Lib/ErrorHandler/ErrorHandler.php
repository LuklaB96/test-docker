<?php
namespace App\Lib\ErrorHandler;

use App\Lib\Config;

class ErrorHandler
{
    private string $errorRoute = '';
    public function __construct(string $errorRoute = '')
    {
        $this->$errorRoute = empty($errorRoute) ? Config::get('ERROR_ROUTE') : $errorRoute;
    }
    public function handle($errno, $errstr, $errfile, $errline)
    {
        header('Location: ' . $this->errorRoute);
    }
}

?>