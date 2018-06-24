<?php

namespace App\Controller\Menu;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController as Controller;

class BaseController extends Controller
{
    protected function getConfig()
    {
        return array(
            array(
                'name' => '../',
                'url' => $this->generateUrl('doc-index'),
            )
        );
    }
}
