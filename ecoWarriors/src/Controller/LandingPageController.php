<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use App\Entity\Product;
use App\Entity\User;

class LandingPageController extends AbstractController
{
    /**
     * @Route("/access", name="landingPage")
     */
    public function index(): Response
    {
        return $this->render('landing_page/landingPage.html.twig', [
            'controller_name' => 'LandingPageController',
        ]);
    }
        // --- Prodact Page ----
        /**
        * @Route("/", name="shop")
        */
        public function prodactPageAction(): Response
        {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll(); 
        return $this->render('landing_page/prodactPage.html.twig', array("products"=>$products)); 
        }

       // --- About Page ----
        /**
        * @Route("/aboutPage", name="aboutPage")
        */
        public function aboutPageAction(): Response
            {
            return $this->render('landing_page/aboutPage.html.twig');
            }

        // --- Contact Page ----
        /**
        * @Route("/contact", name="contact")
        */
        public function contactAction(): Response
            {
            return $this->render('landing_page/contact.html.twig');
            }
    

        // --- User Details Page ----
        /**
    * @Route("/details/{id}", name="userDetails")
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
        
        return $this->render('landing_page/userDetails.html.twig', array(
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
        * @Route("/filter/{category}", name="filter")
        */
        public function filter($category){
            $products = $this->getDoctrine()->getRepository('App:Product')->findByCategory($category);
            return $this ->render('landing_page/filter.html.twig', array('products'=>$products));
    }


        /**
     * @Route("/edityourself", name="edityourself", methods={"GET","POST"})
     */
    public function edityourself(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        //$user = $this->getDoctrine()->getRepository('App:User')->find($id);
        $user = $this->getUser();

        $user->setFirstName($user->getFirstName());
        $user->setLastName($user->getLastName());
        $user->setEmail($user->getEmail());
        $user->setPassword($user->getPassword());
        //$user->setRoles($user->getRoles());

        $form = $this->createFormBuilder($user)

        ->add('firstName', TextType::class, array('attr' => array ('class'=>'form-control  mb-2')))
        ->add('lastName', TextType::class, array('attr' => array ('class'=>'form-control mb-2')))
        ->add('email', TextType::class, array('attr' => array ('class'=>'form-control mb-2')))
        //->add('roles', ChoiceType::class, ['choices'=> ['User'=> 'ROLE_USER', 'Admin'=>'ROLE_ADMIN']])


        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ], 
        ])


        ->add('save', SubmitType::class, array('label'=> 'Update User' , 'attr' => array( 'class'=> 'btn btn-outline-info mt-3', 'style' =>'margin-botton:15px')))
        ->getForm();

        $form->handleRequest($request);
           
        if($form->isSubmitted() && $form->isValid()){
            $firstName = $form['firstName']->getData();
            $lastName = $form['lastName']->getData();
            $email = $form['email']->getData();
            

            $em = $this->getDoctrine()->getManager();
            //$user = $em->getRepository('App:User')->find($id);
            $user = $this->getUser();

            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setPassword(
                    $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $em->flush();
            $this->addFlash(
                    'notice',
                    'User Updated'
            );
            return $this ->redirectToRoute('edityourself');

        }


        return  $this->render( 'landing_page/edityourself.html.twig', array( 'user' => $user, 'form' => $form->createView()));

    
    /* public function edit(Request $request, User $user): Response
        {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);  */

    }


    /**
        * @Route("/accessdenied", name="accessdenied")
        */
        public function accessdenied(){

            return $this ->render('accessdenied.html.twig');
    }


    
    /**
     * @Route("/deleteyourself/{id}", name="deleteyourself", methods={"DELETE"})
     */
    public function delete(Request $request, User $user,TokenStorageInterface $tokenStorage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $tokenStorage->setToken();

            $entityManager->remove($user);
            
            $entityManager->flush();

        }

        return $this->redirectToRoute('landingPage');
    }
    


}
