<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Genus;
use AppBundle\Entity\GenusNote;
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

        $genusNote = new GenusNote();
        $genusNote->setUsername('AquaWeaver');
        $genusNote->setUserAvatarFilename('ryan.jpeg');
        $genusNote->setNote('I counted 8 legs.... as they wrapped around me');
        $genusNote->setCreatedAt(new \DateTime('-1 month'));
        $genusNote->setGenus($genus);

        // Use the entity manager service to query and save
        $em = $this->getDoctrine()->getManager();
        // Tell doctrine that you want to save the obj
        $em->persist($genus);
        $em->persist($genusNote);
        // finally doctrine figures what query to use and savas the obj
        $em->flush();

        return new Response('<html><body>Genus generated</body></html>');
    }

    /**
     * @Route("/genus")
     */
    public function listAction()
    {
        //get entity manager
        $em = $this->getDoctrine()->getManager();
        // returns a repository object
        $genuses = $em->getRepository('AppBundle:Genus')
            ->findAllPublishedOrderedByRecentlyActive();

        return $this->render('genus/list.html.twig',[
            'genuses' => $genuses
        ]);
    }
    // Service : Useful objects
    // Associative array called The container (Container is an object !!!)
    // Each object has a key ->mailer -> mailer object
    /**
     * @Route("/genus/{genusName}", name="genus_show")
     */
    public function showAction($genusName)
    {
        $em = $this->getDoctrine()->getManager();
        $genus = $em->getRepository('AppBundle:Genus')
            ->findOneBy(['name'=> $genusName]);


        if (!$genus){
            throw $this->createNotFoundException('No genus Found');
        }
//        // get the my_markdown_cache servive from DoctrineCacheBundle
//        // configured under the name my_markdown_cache
//        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
//        // Make sure the same string doesn't parsed twice
//        // Add a key to the string
//        $key = md5($funFact);
//
//        if ($cache->contains($key)){
//            $funFact = $cache->fetch($key);
//        } else {
//            // Parse through markdown
//            sleep(1);
//            $funFact = $this->get('markdown.parser')->transform($funFact);
//            $cache->save($key,$funFact);
//        }

        $recentNotes = $em->getRepository('AppBundle:GenusNote')
            ->findAllRecentNotesForGenus($genus);

        return $this->render('genus/show.html.twig',[
            'genus' => $genus,
            'recentNotesCount' => count($recentNotes)
        ]);

    }

    // Method get --Without this route will match this request using any http method (post,delete,get) Optional
    /**
     * @Route("/genus/{name}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction(Genus $genus)
    {
        $notes = [];
        foreach ($genus->getNotes() as $note)
        {
            $notes[] = [
                'id' => $note->getId(),
                'username' => $note->getUsername(),
                'avatarUri' => '/images/'.$note->getUserAvatarFilename(),
                'notes' => $note->getNote(),
                'date' => $note->getCreatedAt()->format('M d, Y')
            ];
        }

        // json structure
        $data = [
            'notes' => $notes
        ];

        //it calls json_encode
        //it sets application/json content type header on the response
        return new JsonResponse($data);
    }
}