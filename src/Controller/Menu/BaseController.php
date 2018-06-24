<?php
namespace App\Controller\Menu;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController as Controller;

class BaseController extends Controller
{
    public function index()
    {
        return $this->render('base/api.html', $this->getConfig());
    }
    protected function getConfig()
    {
        return array(
            'uri' => '/menu',
            'params' => array(
                array(
                    'name' => 'key',
                    'type' => 'string',
                    'default' => '0',
                    'must' => '',
                ),
                array(
                    'name' => 'name',
                    'type' => 'string',
                    'default' => '',
                    'must' => '是',
                ),
            ),
            'return' => $this->toJs(array(
                'uri' => 'menu',
                'params' => array(
                    array(
                        'name' => 'key',
                        'type' => 'string',
                        'default' => '0',
                        'must' => true,
                    ),
                    array(
                        'name' => 'key',
                        'type' => 'string',
                        'default' => '0',
                        'must' => true,
                    )
                ),
                'return' => array(

                )
            )),
        );
    }
}
