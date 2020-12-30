<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Post;
use App\Form\PostType;
use App\Service\UploadHelper;
use App\View\PostDetailView;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{
    /**
     * Post detail
     *
     * @param Post $post
     * @param PostDetailView $view
     * @return Response
     * @see /config/routes.yaml
     */
    public function detail(Post $post, PostDetailView $view): Response
    {
        return $this->render('post/detail.html.twig', $view->create($post));
    }

    /**
     * Add new post - Renders and handles the new post form
     *
     * @param Request $request
     * @param UploadHelper $uploadHelper
     * @see /config/routes.yaml
     * @return Response
     */
    public function new(Request $request, UploadHelper $uploadHelper): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $photoFile */
            if ($photoFile = $form['photo']->getData()) {
                $newFilename = $uploadHelper->uploadPostPhoto($photoFile);
                $post->setPhotoFilename($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Post like AJAX - Returns JSON response of true when post has been successfully liked
     *                  and a JSON response of false when post has been successfully unliked
     *
     * @param Post $post
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postLikeAjax(Post $post, EntityManagerInterface $entityManager) {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        if ($like = $post->getReactionForUser($user)) {
            $entityManager->remove($like);
            $response = false;
        } else {
            $like = new Like($user, $post);
            $entityManager->persist($like);
            $response = true;
        }

        $entityManager->flush();

        return $this->json($response, Response::HTTP_OK);
    }

    /**
     * Post like fallback
     *
     * @param Post $post
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function postLike(
        Post $post,
        EntityManagerInterface $entityManager,
        Request $request
    ) {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        if ($like = $post->getReactionForUser($user)) {
            $entityManager->remove($like);
        } else {
            $like = new Like($user, $post);
            $entityManager->persist($like);
        }

        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'), 301);
    }

    /**
     * Post delete - Handles the delete post request
     *
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function delete(Request $request, Post $post): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('index');
    }
}
