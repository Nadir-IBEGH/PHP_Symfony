<?php

namespace App\Controller;

use useSymfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController
{

    /**
     * @Route("/test/{age<\d+>?0}")
     * @param Request $request
     * @param $age
     * @return Response
     */
    public function test(Request $request, $age)
    {
       // $age=$request->attributes->get('age');
        return  new Response("vous avez $age ans");
    }

}