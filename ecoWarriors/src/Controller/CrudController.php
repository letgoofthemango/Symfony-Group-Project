<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Product;
use App\Entity\Producer;

/**
    * @Route("/crud")
    */


class CrudController extends AbstractController
{

    /**
    * @Route("/", name="indexAction")
    */ 
    public function indexAction()
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll(); // this variable $Names will store the result of running a query to find all the Names
        return $this->render('crud/index.html.twig', array("products"=>$products)); // i send the variable that have all the Names as an array of objects to the index.html.twig page
    }

    /**
    * @Route("/create", name="create_page")
    */
    public function createAction(Request $request)
    {
        $product = new Product;

        $form = $this->createFormBuilder($product)
        ->add('productName', TextType::class, array('attr' => array('class'=> 'form-control w-75', 'style'=>'margin-bottom:15px')))
        //->add('category', TextType::class, array('attr' => array('class'=> 'form-control w-75', 'style'=>'margin-bottom:15px')))
        ->add('category', ChoiceType::class, array( 'choices'=>array('fruit'=> 'fruit', 'vegetable'=>'vegetable', 'bread'=>'bread', 'cheese'=>'cheese', 'basket'=>'basket'),'attr' => array('class'=> 'form-control w-75', 'style'=>'margin-botton:15px')))
        ->add('price', TextType::class, array('attr' => array('class'=> 'form-control w-75', 'style'=>'margin-bottom:15px')))
        ->add('img', TextType::class, array('attr' => array('class'=> 'form-control w-75', 'style'=>'margin-bottom:15px')))
        ->add('description', TextAreaType::class, array('attr' => array('class'=> 'form-control w-75', 'style'=>'margin-bottom:15px')))
        ->add('producer', EntityType::class, array ("class"=> Producer::class, "choice_label"=> "producerName",'attr' => array('class'=> 'form-control w-75', 'style'=>'margin-bottom:15px')))

        ->add('save', SubmitType::class, array('label'=> 'Create New Item', 'attr' => array('class'=> 'btn btn-outline-info mt-3', 'style'=>'margin-bottom:15px')))
        ->getForm();
        
        $form->handleRequest($request);
        /* Here we have an if statement, if we click submit and if  the form is valid we will take the values from the form and we will save them in the new variables */
        if($form->isSubmitted() && $form->isValid()){ //fetching data
             // taking the data from the inputs by the name of the inputs then getData() function
            $name = $form['productName']->getData();
            $category = $form['category']->getData();
            $price = $form['price']->getData();
            $img = $form['img']->getData();
            $description = $form['description']->getData();

            //Here we will get the current date
            //$now = new\DateTime('now');
 
            /* these functions we bring from our entities, every column has a set function and we put the value that we get from the form */
            $product->setProductname($name);
            $product->setCategory($category);
            $product->setPrice($price);
            $product->setImg($img);
            $product->setDescription($description);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($product); // build the query
            $em->flush(); // like you run the query

            $this->addFlash(
                    'success',
                    'product added'
                    );
            return $this->redirectToRoute('indexAction');
        }
        /* now to make the form we will add this line form->createView() and now you can see the form in create.html.twig file  */
        return $this->render('crud/create.html.twig', array('form' => $form->createView()));
    }

    /**
    * @Route("/edit/{id}", name="product_edit")
    */
    public function editAction( $id, Request $request)
    {
        $product = $this->getDoctrine()->getRepository('App:Product')->find($id);
        /* Now we will use set functions and inside this set functions we will bring the value that is already exist using get function for example we have setName() and inside it we will bring the current value and use it again */
        $product->setProductname($product->getProductname());
        $product->setCategory($product->getCategory());
        $product->setPrice($product->getPrice());
        $product->setImg($product->getImg());
        $product->setDescription($product->getDescription());
        
        /* Now when you type createFormBuilder and you will put the variable product the form will be filled of the data that you already set it */
        $form = $this->createFormBuilder($product)
        
        ->add('ProductName', TextType::class, array('attr' => array ('class'=> 'form-control w-75', 'style'=> 'margin-bottom:15px')))
        //->add('category', TextType::class, array('attr' => array('class' => 'form-control w-75', 'style'=>'margin-botton:15px' )))
        ->add('category', ChoiceType::class, array( 'choices'=>array('fruit'=> 'fruit', 'vegetable'=>'vegetable', 'bread'=>'bread', 'cheese'=>'cheese', 'basket'=>'basket'),'attr' => array('class'=> 'form-control w-75', 'style'=>'margin-botton:15px')))
        ->add('price', TextType::class, array('attr' => array('class' => 'form-control w-75', 'style'=>'margin-botton:15px' )))
        ->add('img', TextType::class, array('attr' => array('class' => 'form-control w-75', 'style'=>'margin-botton:15px' )))
        ->add('description', TextAreaType::class, array('attr' => array('class' => 'form-control w-75', 'style'=>'margin-botton:15px' )))
        ->add('producer', EntityType::class, array ("class"=> Producer::class, "choice_label"=> "producerName",'attr' => array('class'=> 'form-control w-75', 'style'=>'margin-bottom:15px')))

        ->add('save', SubmitType::class, array('label'=> 'Update Product' , 'attr' => array( 'class'=> 'btn btn-outline-info mt-3', 'style' =>'margin-botton:15px')))
        ->getForm();

        $form->handleRequest($request);
           
        if($form->isSubmitted() && $form->isValid()){
            //fetching data from the form
            $names = $form['ProductName']->getData();
            $category = $form['category']->getData();
            $price = $form['price']->getData();
            $img = $form['img']->getData();
            $description = $form['description']->getData();

            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('App:product')->find($id);

            $product->setProductname($names);
            $product->setCategory($category);
            $product->setPrice($price);
            $product->setImg($img);
            $product->setDescription($description);            
        

            $em->flush();
            $this->addFlash(
                    'notice',
                    'product Updated'
            );
            return $this ->redirectToRoute('indexAction');
        }
        return  $this->render('crud/edit.html.twig', array( 'product' => $product, 'form' => $form->createView()));
    }


    /**
    * @Route("/details/{id}", name="details_page")
    */
    public function detailsAction($id)
    {
        $product = $this->getDoctrine()->getRepository('App:Product')->find($id);

        $producerName = $product->getProducer()->getProducername();
        $producerStreet = $product->getProducer()->getAddress()->getStreet();
        $producerNumber = $product->getProducer()->getAddress()->getNumber();
        $producerZIP = $product->getProducer()->getAddress()->getZipcode();
        $producerCity = $product->getProducer()->getAddress()->getCity();
        $producerCountry = $product->getProducer()->getAddress()->getCountry();
        
        return $this->render('crud/details.html.twig', array(
            'product'=>$product,
            'producerName'=>$producerName,
            'street'=>$producerStreet,
            'number'=>$producerNumber,
            'ZIP'=>$producerZIP,
            'city'=>$producerCity,
            'country'=>$producerCountry
        ));
    }

    /**
    * @Route("/delete/{id}", name="delete")
    */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('App:Product')->find($id);
        //dd($product);
        $em->remove($product);
        $em->flush();

        $this->addFlash(
            'notice',
            'Product removed'
        );
        return  $this->redirectToRoute('indexAction');
    }

  
}