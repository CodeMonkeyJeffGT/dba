<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * 显示所有可用方法
     */
    public function index()
    {
        return $this->render('base/index.html', array(
            'tree' => $this->getConfig()
        ));
    }

    /**
     * 获取当前层级所有可用接口/下级目录
     */
    protected function getConfig()
    {
        return array(
            array(
                'name' => 'Menu/',
                'url' => $this->generateUrl('doc-menu'),
            ),
            array(
                'name' => 'Tablesearch/',
                'url' => $this->generateUrl('doc-tablesearch'),
            ),
            array(
                'name' => 'Multiselect/',
                'url' => $this->generateUrl('doc-multiselect'),
            ),
        );
    }
}
