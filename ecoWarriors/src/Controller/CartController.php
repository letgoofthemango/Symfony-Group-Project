<?php

namespace App\Controller;
use App\Entity\Product;
use App\Entity\Cart;
use App\Entity\Address;
use App\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    /**
     * @Route("/add/{id}", name="add_to_cart")
     */
    public function addToCart($id): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $cartCheck = $this->getDoctrine()->getRepository(Cart::class)->findByFkProduct($product);
        if(empty($cartCheck)){
            // dd($product);
        $cart = new Cart();
        // dd($this->getUser());
        $cart->setFkProduct($product);
        $cart->setFkUser($this->getUser());
        $cart->setQty(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($cart);
        $em->flush();
        
        }else {
            $em = $this->getDoctrine()->getManager();
            $qtty = $cartCheck[0]->getQty() + 1;
            $cartCheck[0]->setQty($qtty);
            $em->persist($cartCheck[0]);
            $em->flush();
        }

        
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/deleteFromCart/{id}", name="delete_from_cart")
     */
    public function deleteFromCart($id): Response
    {
        $cart = $this->getDoctrine()->getRepository(Cart::class)->find($id);
        // dd($product);
        $em = $this->getDoctrine()->getManager();
        $em->remove($cart);
        $em->flush();
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/remove/{id}", name="remove_from_cart")
     */
    public function removeFromCart($id): Response
    {
        $cart = $this->getDoctrine()->getRepository(Cart::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        if($cart->getQty() == 1){  
            $em->remove($cart);
        }else {
            $newQty = $cart->getQty() - 1 ;
            $cart->setQty($newQty);
            $em->persist($cart);
        }
        
        $em->flush();
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/plus/{id}", name="plus_cart")
     */
    public function plusCart($id): Response
    {
        $cart = $this->getDoctrine()->getRepository(Cart::class)->find($id);
        $em = $this->getDoctrine()->getManager();
            $newQty = $cart->getQty() + 1 ;
            $cart->setQty($newQty);
            $em->persist($cart);
        
        
        $em->flush();
        return $this->redirectToRoute("cart");
    }


    /**
     * @Route("/cart", name="cart")
     */

    public function index(): Response
    {

        $cart = $this->getDoctrine()->getRepository('App:Cart')->findByFkUser( $this->getUser()->getId());
        // dd($cart);
        $order = new Orders();
        return $this->render("cart/index.html.twig",array("cart"=>$cart));
        
    }
    
    /**
     * @Route("/cart/order", name="order")
     *
     */

    public function orderAction(Request $request): Response
    {

        $cart = $this->getDoctrine()->getRepository('App:Cart')->findByFkUser( $this->getUser()->getId());
         // dd($cart);
        $order= new Orders();
        $address = new Address();
         $form2 = $this->createFormBuilder($address)
         ->add('street', TextType::class, array('attr' => array('class' => 'form-control w-75', 'style'=>'margin-botton:15px' )))
         ->add('number', TextType::class, array('attr' => array('class' => 'form-control w-75', 'style'=>'margin-botton:15px' )))
         ->add('city', TextType::class, array('attr' => array('class' => 'form-control w-75', 'style'=>'margin-botton:15px' )))
         ->add('zipcode', TextType::class, array('attr' => array('class' => 'form-control w-75', 'style'=>'margin-botton:15px' )))
         ->add('country', TextType::class, array('attr' => array('class' => 'form-control w-75', 'style'=>'margin-botton:15px' )))->getForm();

         $form = $this->createFormBuilder($order)
        //->add('category', TextType::class, array('attr' => array('class' => 'form-control w-75', 'style'=>'margin-botton:15px' )))
        ->add('deliveryDate', DateType::class, array('attr' => array( 'style'=>'margin-botton:15px')))
        
        ->getForm();

        $form->handleRequest($request);
        $form2->handleRequest($request);
        
        if($form2->isSubmitted()){
            //fetching data from the form
            $street = $form2['street']->getData();
            $number = $form2['number']->getData();
            $city = $form2['city']->getData();
            $zipcode = $form2['zipcode']->getData();
            $country = $form2['country']->getData();

            $em = $this->getDoctrine()->getManager();
            

            $address->setStreet($street);
            $address->setNumber($number);
            $address->setCity($city);
            $address->setZipcode($zipcode);
            $address->setCountry($country);
            
            // $address->setDeliveryDate($deliveryDate);             
            $em->persist($address); // build the query

            $em->flush();

            $deliveryDate = $form['deliveryDate']->getData();
            $now = new \DateTime( 'now' );
            foreach($cart as $product){
                $order = new Orders();
                $order->setOrderDate($now);
                $order->setDeliveryDate($deliveryDate); 
                $order->setUsers($this->getUser());
                $order->setProduct($product->getFkProduct());
                $order->setAddress($address);
                // dd($order);
                $em->persist($order); // build the query

                $em->flush();
            }
            foreach($cart as $product){
                $em->remove($product);
                $em->flush();
            }
            
            // $order->setDeliveryDate($deliveryDate); 
           
            $this->addFlash(
                    'Success',
                    'Order In Process'
            );
            return $this ->redirectToRoute('shop');
        }
        return  $this->render('order/index.html.twig', array(  'form' => $form->createView(),
        'form2' => $form2->createView()));
        
    
    
}
    
}
