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
     * @Route("/", name="homepage")
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
     * @Route("create_product", name="create_product")
     */
    public function createProduct(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $p = new Product();
        $p->setTitle("Livre")->setDescription("Un livre fort intéressant")->setPrice(42.12);

        $em->persist($p);
        $em->flush();

        return new Response("Saved ID " . $p->getId());
    }

    /**
     * @Route("show_product/{id}", name="show_product")
     */
    public function showProduct($id){
        $p = $this->getDoctrine()->getRepository('AppBundle:Product')
        ->find($id);
        return $this->render("@App/default/show_product.html.twig", ["p" => $p]);
    }

    /**
     * @Route("products.{_format}",
     *     name="product",
     *     requirements={"_format": "html|json"},
     *     defaults={"_format": "html"}
     * )
     */
    public function getProducts(Request $request){
        $colonne = $request->query->get("colonne");
        $order = $request->query->get("order");
        if (!$colonne)
            $colonne = 'price';
        if(!$order)
            $order = 'asc';
        // Trier par prix et par titre
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')
            ->findAllAsArray($colonne, $order);

        if ($request->getRequestFormat() === 'json')
            return new JsonResponse($products);

        return $this->render("@App/default/products.html.twig", [
            "products" => $products,
            'url' => $this->generateUrl('product')]);
    }

    /**
     * @Route("protegee", name="protegee")
     */
    public function routeSecurisee(){
        return new Response("Page protégée");
    }
}
