<?php
namespace Ccs\Web\App\Controller;

use Ccs\Lib\Console;
use Ccs\Web\Lib\Controller;
use Ccs\Web\Lib\View;

class Hello extends Controller
{
    public function world()
    {
        if ($this->request->getQueryString('name')) {
            echo $this->request->getQueryString('name') . ',';
        }
        return View::display(__CCS_STATIC_PATH__ . "/hello.html");
    }
}