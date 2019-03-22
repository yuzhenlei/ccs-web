<?php
namespace Web\App\Controller;

use Web\Lib\View;

class Hello 
{
    public function world()
    {
        return View::display(__CCS_STATIC_PATH__ . "/hello.html");
    }
}