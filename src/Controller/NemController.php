<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Nem;

use App\Form\CommentType;
use App\Form\ImageType;
use App\Form\NemType;
use App\Repository\NemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NemController extends AbstractController
{
    #[Route('/nem', name: 'app_nems')]
    public function index(NemRepository $nemRepository): Response
    {
        return $this->render('nem/index.html.twig', [
            'controller_name' => 'NemController',
            "nems"=>$nemRepository->findAll(),
        ]);
    }

    #[Route('/nem/{id}', name: 'app_show')]
    public function show(Nem $nem): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);

        return $this->render('nem/show.html.twig', [
            'controller_name' => 'NemController',
            "nem"=>$nem,
            "form"=>$form->createView(),


        ]);
    }

    #[Route('/create', name: 'app_create')]
    public function create(Nem $nem, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$this->getUser()){return $this->redirectToRoute("app_nems");}

        $nem = new Nem();
        $form = $this->createForm(NemType::class, $nem);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $nem->setAuthor($this->getUser());


            $nem->setCreatedAt(new \DateTime());
            $manager->persist($nem);
            $manager->flush();

            return $this->redirectToRoute("app_nems");
        }




        return $this->render('nem/create.html.twig', [
            "nem"=>$nem,
            "form"=>$form->createView(),
            "btnValue"=>"Créer"


        ]);
    }

    #[Route('/nem/delete/{id}', name: 'app_delete')]
    public function delete(EntityManagerInterface $manager, Nem $nem):Response
    {
        if($this->getUser() === $nem->getAuthor()) {


            $manager->remove($nem);
            $manager->flush();

            return $this->redirectToRoute("app_nems");
        }
        else{
            return $this->redirectToRoute("app_nems");

        }



    }

    #[Route('/nem/edit/{id}', name: 'app_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, Nem $nem):Response
    {
        $form = $this->createForm(NemType::class, $nem);

        if($this->getUser() === $nem->getAuthor()) {

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($nem);
                $manager->flush();

                return $this->redirectToRoute("app_show", ["id" => $nem->getId()]);
            }
        }else{
            return $this->redirectToRoute('app_nems');
        }


        return $this->render('nem/create.html.twig', [
            "form"=>$form->createView(),
            "btnValue"=>"Modifier"
        ]);

    }

    #[Route('/nem/image/add/{id}', name:"nem_add_image")]
    public function addImage(Nem $nem, Request $request, EntityManagerInterface $manager):Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $image->setNem($nem);
            $manager->persist($image);
            $manager->flush();
            return $this->redirectToRoute("app_show", ["id" => $nem->getId()]);

        }

        return $this->render("nem/addImage.html.twig", ["form"=>$form->createView()]);
    }




}

