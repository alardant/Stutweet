<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine, Request $request, PostRepository $repository): Response
    {

        $search = $request->request->get("search"); // $_POST('search') - le name doit etre cohérent avec celui du formulaire
        $posts = $repository->findAll();
        if ($search) {
            $posts = $repository->findBySearch($search);
        }

        return $this->render(
            'post/index.html.twig',
            [
                'posts' => $posts
            ]
        );
    }

    #[Route('/post/new')]
    public function create(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('uploads'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dump($e); // A changer pour mieux traiter
                }

                $post->setImage($newFilename);
            }


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

        $imageFileName = $post->getImage();
        $pathToFile = 'uploads/' . $imageFileName;
        if (file_exists($pathToFile)) {
            unlink($pathToFile);
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
