<?php

namespace App\Controller\Table;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    private $tableSearch;
    private $buttons;
    private $colums;
    private $tableData;
    private $actions;

    protected function return($data = false): JsonResponse
    {
        if (false === $data) {
            $data = array(
                'tableSearch' => $this->tableSearch,
                'buttons' => $this->buttons,
                'colums' => $this->colums,
                'tableData' => $this->tableData,
                'actions' => $this->actions,
            );
        }
        return parent::return($data);
    }

    protected function setTableSearch($tableSearch)
    {
        $this->tableSearch = $tableSearch;
    }

    protected function setButtons($buttons)
    {
        $this->buttons = $buttons;
    }

    protected function setColums($colums)
    {
        $this->colums = $colums;
    }

    protected function setTableData($tableData)
    {
        $this->tableData = $tableData;
    }

    protected function setActions($actions)
    {
        $this->actions = $actions;
    }
}
