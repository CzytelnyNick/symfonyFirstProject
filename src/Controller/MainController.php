<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/custom/{name?}', name: 'custom')]
    public function custom(Request $request) {
        $name = $request->get('name');
        return $this -> render('custom/custom.html.twig', [
            'name' => $name
        ]);

    }
}
