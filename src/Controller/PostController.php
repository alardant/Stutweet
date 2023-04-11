<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        return $this->render(
            'post/index.html.twig'
        );
    }

    #[Route('/post/new')]
    public function create(Request $request): Response
    {

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        dump($post);
        $form->handleRequest($request);
        dump($post);
        if ($form->isSubmitted() && $form->isValid()) {
            // var_dump($post);
        }
        return $this->render('post/form.html.twig', [
            'post_form' => $form->createView()
        ]);
    }
}
