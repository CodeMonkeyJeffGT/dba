<?php

namespace App\Controller\Multselect;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Multselect\BaseController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Teacher;

class TeacherController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $this->setMultselect($teacherDb->getMultselect());     
        return $this->return();
    }
}
