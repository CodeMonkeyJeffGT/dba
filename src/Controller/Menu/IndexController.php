<?php

namespace App\Controller\Menu;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Menu\BaseController as Controller;

class IndexController extends Controller
{
    /**
     * @Route("/menu/index", name="menu_index")
     */
    public function index()
    {
        return $this->render('menu/index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
