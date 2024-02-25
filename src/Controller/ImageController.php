<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Nem;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    #[Route('/image/add/nem/{id}', name: 'add_nem_image')]
    #[Route('/image/add/comment/{id}', name: 'add_comment_image')]
    public function index($id, Request $request, EntityManagerInterface $manager): Response
    {
        $route = $request->attributes->get("_route");

        switch ($route){

            case 'add_nem_image':
                $entity = Nem::class;
                $setter = "setNem";
                $redirectRoute = "nem_image";
                $routeParam= ["id"=>$id];
                break;
            case 'add_comment_image':
                $entity = Comment::class;
                $setter = "setComment";
                $redirectRoute = "comment_image";
                $routeParam= ["id"=>$id];
                break;
        }


        $toBeAddedAnImage = $manager->getRepository($entity)->find($id);


        //en fonction de la route, récuperer la bonne entité

        $image = new Image();
        $formImage = $this->createForm(ImageType::class, $image);
        $formImage->handleRequest($request);
        if($formImage->isSubmitted() && $formImage->isValid())
        {

            $image->$setter($toBeAddedAnImage);
            $manager->persist($image);
            $manager->flush();

        }



        return $this->redirectToRoute($redirectRoute, $routeParam);
    }

    #[Route('/image/delete/nem/{id}', name: 'delete_nem_image')]
    #[Route('/image/delete/nem/{id}', name: 'delete_comment_image')]
    public function delete(EntityManagerInterface $manager, Image $image,Request $request){



        $nem = $image->getNem();
        $comment = $image->getNem();

        $manager->remove($image);
        $manager->flush();


        return $this->redirectToRoute("nem_image", ["id"=>$comment->getId()]
        );
    }
}
