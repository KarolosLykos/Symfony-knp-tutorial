<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// To get access to the service container you have to extend Symfony's baseController
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class GenusController extends Controller
{
    // Service : Useful objects
    // Associative array called The container (Container is an object !!!)
    // Each object has a key ->mailer -> mailer object
    /**
     * @Route("/genus/{genusName}")
     */
    public function showAction($genusName)
    {

        $notes = [
            '// Each object has a key ->mailer -> mailer object',
            '// Each object has a key ->mailer -> mailer object',
            '// Each object has a key ->mailer -> mailer object'
        ];

        return $this->render('genus/show.html.twig',[
            'name' => $genusName,
            'notes' => $notes
        ]);

    }
}