<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/3/2017
 * Time: 1:56 μμ
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function homepageAction()
    {
        return $this->render('main/homepage.html.twig');
    }
}