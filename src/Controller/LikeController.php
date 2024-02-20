<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Nem;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LikeController extends AbstractController
{
    #[Route('/like/nem/{id}', name: 'app_like')]
    public function index(Nem $nem, LikeRepository $likeRepository, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();


        if($nem->isLikedBy($user)){
            $like = $likeRepository->findOneBy([
                'author'=>$user,
                'nem'=>$nem
            ]);
            $manager->remove($like);

        }else{
            $like = new Like();
            $like->setNem($nem);
            $like->setAuthor($user);
            $manager->persist($like);
        }
        $manager->flush();

        return $this->redirectToRoute("app_show", ["id"=>$nem->getId()]);
    }
}
