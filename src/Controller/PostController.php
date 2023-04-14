<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PostType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Post::class);
        $posts = $repository->findAll();

        return $this->render(
            'post/index.html.twig',
            [
                'posts' => $posts
            ]
        );
    }

    #[Route('/post/new')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setPublishedAt(new \DateTime());
            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('post/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/post/delete/{id}', name: 'delete_post', requirements: ['id' => '\d+'])]
    public function delete(Post $post, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser() !== $post->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer une publication qui ne vous appartient pas.');

            return $this->redirectToRoute('home');
        }
        $em = $doctrine->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('home');
    }

    #[Route('/post/edit/{id}', name: 'edit_post', requirements: ['id' => '\d+'])]
    public function update(Request $request, Post $post, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser() !== $post->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas dupliquer une publication qui ne vous appartient pas.');
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('post/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/post/copy/{id}', name: 'copy_post', requirements: ['id' => '\d+'])]
    public function duplicate(Request $request, Post $post, ManagerRegistry $doctrine): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser() !== $post->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier une publication qui ne vous appartient pas.');
            return $this->redirectToRoute('home');
        }
        $copyPost = clone ($post);
        $em = $doctrine->getManager();
        $em->persist($copyPost);
        $em->flush();
        return $this->redirectToRoute('home');
    }
}
