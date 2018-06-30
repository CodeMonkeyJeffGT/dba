<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    protected $request;

    protected function request($name, $default = null)
    {
        if (is_null($this->request)) {
            $this->request = json_decode(file_get_contents('php://input'), true);
        }
        if (isset($this->request[$name])) {
            return $this->request[$name];
        } else {
            return $default;
        }
    }

    protected function return($data): JsonResponse
    {
        $response = $this->json(array(
            'data' => $data,
            'errno' => 0,
            'errmsg' => 'OK',
        ));
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:8080');
        $response->headers->set('Access-Control-Allow-Headers', 'token, Origin, X-Requested-With, Content-Type, Accept');
        $response->headers->set('Access-Control-Allow-Methods', 'PUT,POST,GET,DELETE,OPTIONS');
        $response->headers->set('X-Powered-By', '3.2.1');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        return $response;
    }

    protected function confirm($msg): JsonResponse
    {
        $response = $this->json(array(
            'data' => null,
            'errno' => 2,
            'errmsg' => $msg,
        ));
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:8080');
        $response->headers->set('Access-Control-Allow-Headers', 'token, Origin, X-Requested-With, Content-Type, Accept');
        $response->headers->set('Access-Control-Allow-Methods', 'PUT,POST,GET,DELETE,OPTIONS');
        $response->headers->set('X-Powered-By', '3.2.1');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        return $response;
    }

    protected function error($msg): JsonResponse
    {
        $response = $this->json(array(
            'data' => null,
            'errno' => 1,
            'errmsg' => $msg,
        ));
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:8080');
        $response->headers->set('Access-Control-Allow-Headers', 'token, Origin, X-Requested-With, Content-Type, Accept');
        $response->headers->set('Access-Control-Allow-Methods', 'PUT,POST,GET,DELETE,OPTIONS');
        $response->headers->set('X-Powered-By', '3.2.1');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        return $response;
    }
}
