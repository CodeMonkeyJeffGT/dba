<?php

namespace App\Controller\Table;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Table\BaseController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Exam;

class ExamController extends Controller
{
    public function search(): JsonResponse
    {
        $examDb = $this->getDoctrine()->getRepository(Exam::class);
        return $this->return();
    }

    public function edit(): JsonResponse
    {
        $examDb = $this->getDoctrine()->getRepository(Exam::class);
        return $this->return();
    }

    public function delete(): JsonResponse
    {
        $examDb = $this->getDoctrine()->getRepository(Exam::class);
        return $this->return();
    }

    public function remind(): JsonResponse
    {
        $examDb = $this->getDoctrine()->getRepository(Exam::class);
        return $this->return();
    }
}
