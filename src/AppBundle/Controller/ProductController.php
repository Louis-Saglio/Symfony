<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 23/10/17
 * Time: 11:51
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
     * @Route("product_show/{id}", name="show_product")
     */
    public function showProduct($id){
        $p = $this->getDoctrine()->getRepository('AppBundle:Product')
            ->find($id);
        return $this->render("@App/products/show_product.html.twig", ["p" => $p]);
    }

    /**
     * @Route("index.{_format}",
     *     name="product",
     *     requirements={"_format": "html|json"},
     *     defaults={"_format": "html"}
     * )
     * @Route("/", name="homepage")
     */
    public function getProducts(Request $request){
        $colonne = $request->query->get("colonne");
        $order = $request->query->get("order");
        if (!$colonne)
            $colonne = 'price';
        if(!$order)
            $order = 'desc';

        $form = $this->createFormBuilder()
            ->add('keywords', TextType::class)
            ->add('save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        $session = $request->getSession();
        if ($form->isSubmitted() && $form->isValid()) {
            $keyword = $form->getData()["keywords"];
        } else if ($session->get('keyword')) {
            $keyword = $session->get('keyword');
        } else {
            $keyword = '';
        }
        $session->set("keyword", $keyword);

        $products = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->findAllAsArray($colonne, $order, $keyword);

        if ($order == 'asc') {
            $order = 'desc';
        } else {
            $order = 'asc';
        }

        if ($request->getRequestFormat() === 'json')
            return new JsonResponse($products);

        return $this->render("@App/products/products.html.twig", [
            'products' => $products,
            'url' => $this->generateUrl('product'),
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("product_create", name="create_product")
     */
    public function createProduct(Request $request)
    {
          $p = new Product();
          $form = $this->createForm(ProductType::class, $p);

          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()){
              $p = $form->getData();
              $em = $this->getDoctrine()->getManager();
              $em->persist($p);
              $em->flush();
          }


        return $this->render("@App/products/create_product.html.twig", [
            "form" => $form->createView()
        ]);
    }

}