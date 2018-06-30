<?php
namespace App\Controller\Multselect;

use App\Controller\BaseController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    private $multselect;

    protected function return($data = false): JsonResponse
    {
        if ($data === false) {
            $data = $this->multselect;
        }
        return parent::return($data);
    }

    protected function setMultselect($multselect)
    {
        $this->multselect = $multselect;
    }
}
