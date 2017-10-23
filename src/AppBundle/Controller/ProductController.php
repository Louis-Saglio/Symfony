<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 23/10/17
 * Time: 11:51
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     */
    public function getProducts(Request $request){
        $colonne = $request->query->get("colonne");
        $order = $request->query->get("order");
        if (!$colonne)
            $colonne = 'price';
        if(!$order)
            $order = 'asc';
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')
            ->findAllAsArray($colonne, $order);

        if ($request->getRequestFormat() === 'json')
            return new JsonResponse($products);

        return $this->render("@App/products/products.html.twig", [
            "products" => $products,
            'url' => $this->generateUrl('product')]);
    }

    /**
     * @Route("product_create", name="create_product")
     */
    public function createProduct(Request $request)
    {
          $p = new Product();
          $form = $this->createFormBuilder($p)
              ->add('title', TextType::class)
              ->add('price', MoneyType::class)
              ->add('description', TextareaType::class)
              ->add('submit', SubmitType::class)
              ->getForm();

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