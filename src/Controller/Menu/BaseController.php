<?php
namespace App\Controller\Menu;

use App\Controller\BaseController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    private $menu;

    protected function return($data = false): JsonResponse
    {
        if (false === $data) {
            $data = $this->menu;
        }
        return parent::return($data);
    }

    protected function setMenu($menu)
    {
        $this->menu = $menu;
    }
}
