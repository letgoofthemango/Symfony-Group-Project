<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createFormBuilder($user)

        ->add('firstName', TextType::class, array('attr' => array ('class'=>'form-control mb-2')))
        ->add('lastName', TextType::class, array('attr' => array ('class'=>'form-control mb-2')))
        ->add('email', TextType::class, array('attr' => array ('class'=>'form-control mb-2')))
        // ->add('roles', ChoiceType::class, ['choices'=> ['User'=> "ROLE_USER", 'Admin'=>"ROLE_ADMIN"]])
        //$user->setRoles(array("ROLE_ADMIN"));

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
        ->add('save', SubmitType::class, array('label'=> 'Save User' , 'attr' => array( 'class'=> 'btn btn-outline-info mt-3', 'style' =>'margin-botton:15px')))
        ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $firstName = $form['firstName']->getData();
            $lastName = $form['lastName']->getData();
            $email = $form['email']->getData();
            //$role = $form['role']->getData();

            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            //$user->setRoles($role);
            $user->setPassword(
                    $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user); // build the query
            $em->flush(); // like you run the query

            $this->addFlash(
                    'success',
                    'user added'
                    );
            return $this->redirectToRoute('user_index');
        }
        //now to make the form we will add this line form->createView() and now you can see the form in create.html.twig file 
        return $this->render('user/new.html.twig', array('form' => $form->createView()));


        /* $user = new User();

        $form = $this->createForm(UserType::class, $user);




        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]); */
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/change/{role}&{id}", name="role")
     */
    public function editRole($role,$id): Response
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);

        //$cRole = ($role == 'user' ) ? "['ROLE_USER']" : "['ROLE_ADMIN']";

        //dd($user);
        //dd($role);

        if ($role == 'user' or $role =="[]"){
            //$user->setRoles([]);
            $user->setRoles(array("ROLE_USER"));
        } else if ($role == 'ban'){

            //dd($role);
            $user->setRoles(array("ROLE_BANNED"));
        } else if ($role == 'admin'){
            $user->setRoles(array("ROLE_ADMIN")); 
        } else { }
        
        /* if($cRole == "['ROLE_USER']" || $cRole == "[]" ){
            $user->setRoles(array("ROLE_ADMIN")); 
        } else if ($cRole == "['ROLE_BANNED']") {
            $user->setRoles(array("ROLE_BANNED"));  
        } else {
            $user->setRoles(array("ROLE_USER"));
        } */

        //dd($cRole == "['ROLE_USER']");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this ->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit($id, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getDoctrine()->getRepository('App:User')->find($id);

        $user->setFirstName($user->getFirstName());
        $user->setLastName($user->getLastName());
        $user->setEmail($user->getEmail());
        $user->setPassword($user->getPassword());
        //$user->setRoles($user->getRoles());

        $form = $this->createFormBuilder($user)

        ->add('firstName', TextType::class, array('attr' => array ('class'=>'form-control mb-2')))
        ->add('lastName', TextType::class, array('attr' => array ('class'=>'form-control mb-2')))
        ->add('email', TextType::class, array('attr' => array ('class'=>'form-control mb-2')))
        //->add('roles', ChoiceType::class, ['choices'=> ['User'=> 'ROLE_USER', 'Admin'=>'ROLE_ADMIN']])


        ->add('plainPassword', PasswordType::class,  [
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
            $user = $em->getRepository('App:User')->find($id);

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
            return $this ->redirectToRoute('user_index');

        }

        return  $this->render( 'user/edit.html.twig', array( 'user' => $user, 'form' => $form->createView()));

    
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
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    




}
