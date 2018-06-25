<?php

namespace App\Controller\Table;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Table\BaseController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Teacher;

class TeacherController extends Controller
{
    public function search(): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        return $this->return();
    }
}
