<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Genus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// To get access to the service container you have to extend Symfony's baseController
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class GenusController extends Controller
{
    /**
     * @Route("/genus/new")
     */
    public function newAction()
    {
        // Create a new genus object
        $genus = new Genus();

        // Set the name
        $genus->setName('Octopus'.rand(1,100));
        $genus->setSubFamily('Octopodinae');
        $genus->setSpeciesCount(rand(100, 99999));


        // Use the entity manager service to query and save
        $em = $this->getDoctrine()->getManager();
        // Tell doctrine that you want to save the obj
        $em->persist($genus);
        // finally doctrine figures what query to use and savas the obj
        $em->flush();

        return new Response('<html><body>Genus generated</body></html>');
    }
    // Service : Useful objects
    // Associative array called The container (Container is an object !!!)
    // Each object has a key ->mailer -> mailer object
    /**
     * @Route("/genus/{genusName}")
     */
    public function showAction($genusName)
    {

        $funFact = 'Octopuses can change the color of their body in just *three-tenths* of a second!';

        // get the my_markdown_cache servive from DoctrineCacheBundle
        // configured under the name my_markdown_cache
        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
        // Make sure the same string doesn't parsed twice
        // Add a key to the string
        $key = md5($funFact);

        if ($cache->contains($key)){
            $funFact = $cache->fetch($key);
        } else {
            // Parse through markdown
            sleep(1);
            $funFact = $this->get('markdown.parser')->transform($funFact);
            $cache->save($key,$funFact);
        }



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