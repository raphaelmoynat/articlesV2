<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Nem;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }


    #[Route('/comment/create/{id}', name: 'app_comment_create')]
    public function create(Request $request, EntityManagerInterface $manager, Nem $nem):Response
    {

        if(!$this->getUser()){return $this->redirectToRoute("app_nems");}

        $comment= new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setAuthor($this->getUser());

            $comment->setNem($nem);
            $manager->persist($comment);
            $manager->flush();

        }

        return $this->redirectToRoute("app_show",["id"=>$nem->getId()]);


    }

    #[Route('/comment/delete/{id}', name: 'delete_comment')]
    public function delete(Comment $comment, EntityManagerInterface $manager): Response
    {
        $nem = $comment->getNem();
        if($this->getUser() === $comment->getAuthor()){


            $manager->remove($comment);
            $manager->flush();

            return $this->redirectToRoute('app_show', ["id" => $nem->getId()]);

        }
        else{
            return $this->redirectToRoute('app_nems');
        }




    }

    #[Route('/comment/edit/{id}', name: 'edit_comment')]
    public function edit(Request $request, EntityManagerInterface $manager, Comment $comment):Response
    {
        $nem = $comment->getNem();
        $form = $this->createForm(CommentType::class, $comment);

        if($this->getUser() === $comment->getAuthor()) {



            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setAuthor($this->getUser());

                $manager->persist($comment);
                $manager->flush();

                return $this->redirectToRoute("app_show", ["id" => $nem->getId()]);
            }
        }
        else{
            return $this->redirectToRoute('app_nems');
        }


        return $this->render('comment/edit.html.twig', [

            "form"=>$form->createView(),
        ]);

    }

}