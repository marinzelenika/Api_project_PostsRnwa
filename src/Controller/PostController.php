<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class PostController extends AbstractController
{
    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/post", name="post")
     */
    public function getPost()
    {
        $data = $this->postRepository->findAll();
        return $this->json($data);
    }


    /**
     * @Rest\Get("/update")
     */
    public function update(){
        return $this->json(['message' => 'Updated']);
    }

    /**
     * @Route("/add", name="add_post", methods={"POST"})
     */
    public function add(Request $request){
        $serializer = $this->get('serializer');
        $post = $serializer->deserialize($request->getContent(), Post::class, 'json');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();

        return $this->json($post);
    }
}
