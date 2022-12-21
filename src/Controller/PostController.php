<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\FileUploader;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
#[Route('/post', name: 'post.')]
class PostController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
//        dump($posts);
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);


    }
    #[Route('/create', name: 'create')]
    public function create(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader) {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $entityManager = $doctrine->getManager();
            /** @var UploadedFile $file */
            $file = $request->files->get("post")["Attachment"];
            if($file) {

                $filename = $fileUploader->uploadFiles($file);
                $post->setImage($filename);
                $entityManager->persist($post);
                $entityManager->flush();
            }


            return $this->redirect($this->generateUrl('post.index'));
        }



        $this->addFlash('success', 'Post został dodany');
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);

    }
    #[Route('/show/{id}', name: 'show')]
    public function show(Post $post){

//        $post = $postRepository->findPostUsingCategory($id);
//
//
//        dd($post);

        return $this->render('post/show.html.twig', [
            'post' => $post
            ]);
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Post $post, ManagerRegistry $doctrine) {
        $entityManager=$doctrine->getManager();

        $entityManager->remove($post);

        $entityManager->flush();

        $this->addFlash('success', 'Post został usunięty');
        return $this->redirect($this->generateUrl('post.index'));

    }
}
