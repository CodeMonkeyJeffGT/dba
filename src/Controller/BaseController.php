<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * 显示所有可用方法
     */
    public function index()
    {
        return $this->render('base/doc.html', array(
            'tree' => $this->getConfig()
        ));
    }

    public function return()
    {
        return $this->json($this->getConfig()['return']);
    }

    public function confirm()
    {
        return $this->json($this->getConfig()['confirm']);
    }

    public function error()
    {
        return $this->json($this->getConfig()['error']);
    }

    /**
     * 获取当前层级所有可用接口/下级目录
     */
    protected function getConfig()
    {
        return array(
            array(
                'name' => 'Menu',
                'uri' => $this->generateUrl('api-menu'),
            ),
            array(
                'name' => 'Table',
                'uri' => $this->generateUrl('api-table'),
            ),
            array(
                'name' => 'Multselect',
                'uri' => $this->generateUrl('api-multselect'),
            ),
        );
    }

    /**
     * 数组转换为js数组
     */
    protected function toJs($arr, $level = 1)
    {
        $tpl = "{\n%s},";
        if (array_values($arr) == $arr) {
            $tpl = "[\n%s],";
        }
        $str = "";
        foreach($arr as $key => $value) {
            if (is_array($value)) {
                $value = $this->toJs($value, $level + 1);
            } elseif (is_numeric($value)) {
                $value = $value . ",";
            } else {
                $value = "\"" . $value . "\",";
            }
            if ($tpl != "{\n%s},") {
                $str .= str_repeat(" ", $level * 4) . $value . "\n";
            } else {
                $str .= str_repeat(" ", $level * 4) . $key . ": " . $value . "\n";
            }
        }
        $loc = strrpos($str, ',');
        $str = substr($str, 0, $loc) . substr($str, $loc + 1);
        $str .= str_repeat(" ", $level * 4 - 4);
        $str = sprintf($tpl, $str);
        if ($level == 1) {
            $str = substr($str, 0, strlen($str) - 1);
        }
        return $str;
    }
}
