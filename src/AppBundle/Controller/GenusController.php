<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// To get access to the service container you have to extend Symfony's baseController
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $funFact = 'Octopuses can change the color of their body in just *three-tenths* of a second!';

        $funFact = $this->get('markdown.parser')->transform($funFact);

        return $this->render('genus/show.html.twig',[
            'name' => $genusName,
            'funFact' => $funFact
        ]);

    }

    // Method get --Without this route will match this request using any http method (post,delete,get) Optional
    /**
     * @Route("/genus/{genusName}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction()
    {
        $notes = [
            ['id' => 1, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Octopus asked me a riddle, outsmarted me', 'date' => 'Dec. 10, 2015'],
            ['id' => 2, 'username' => 'AquaWeaver', 'avatarUri' => '/images/ryan.jpeg', 'note' => 'I counted 8 legs... as they wrapped around me', 'date' => 'Dec. 1, 2015'],
            ['id' => 3, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Inked!', 'date' => 'Aug. 20, 2015'],
        ];

        // json structure
        $data = [
            'notes' => $notes
        ];

        //it calls json_encode
        //it sets application/json content type header on the response
        return new JsonResponse($data);
    }
}