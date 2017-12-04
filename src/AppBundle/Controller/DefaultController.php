<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * @Route("haha", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $age = date('Y') - 1998;
        $tab = [1,5,9,2,3,6,8];
        return $this->render('AppBundle:default:index.html.twig', [
            "name"=> "Louis", "age" => $age, "tab" => $tab, 'url' => $this->generateUrl('message')
        ]);
    }

    /**
     * @Route("/test/{name}", name="test")
     */
    public function testAction(Request $request, $name)
    {
        return new Response('<h1>Hello ' . $name . '</h1>');
    }

    /**
     * @Route("/message/{name}", name="message", defaults={"name"=null})
     */
    public function messageAction(Request $request, $name)
    {
        return new Response('<h1>Message pour '.$name.'</h1>');
    }

    /**
     * @Route("protegee", name="protegee")
     */
    public function routeSecurisee(){
        return new Response("Page protégée");
    }

    /**
     * @Route("translate/{langue}", name="translate")
     */
    public function translate(Request $request, $langue){
        $request->setLocale($langue);
        return $this->redirect($request->headers->get('referer'));
    }
}
