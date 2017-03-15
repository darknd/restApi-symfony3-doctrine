<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;


class indexController extends Controller
{
    /**
     * @Route("/")
     */

    public function showMenu()
    {
        $info = [ 'GET' => '/user/{id}',
                  'POST' => '/user',
                  'PUT' => '/user/{id}',
                  'DELETE' => '/user/{id}'
                ];
        //$prettyInfo = json_encode($info);
        return new View($info, Response::HTTP_OK);
    }

}