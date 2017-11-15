<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Theme;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Post;

class PostController extends Controller
{

    /**
     * @param $slug
     * @Route("/post/{slug}",
     *          name="post_details"
     * )
     * @return Response
     */
    public function detailsAction($slug)
    {

        $postRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");

        /* @var $post Post */
        $post = $postRepository->findOneBySlug($slug);

        $answerRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Answer");
        $answers = $answerRepository->getAnswerVote($post->getId());


        if (!$post) {
            throw new NotFoundHttpException("post introuvable");
        }

        return $this->render("post/details.html.twig", [
            "post" => $post,
            "answerList" => $answers
        ]);
    }

    /**
     * @Route("/post-par-annee/{annee}",
     *      name="post_by_year",
     *     requirements={"annee":"\d{4}"})
     * @param $annee
     * @return Response
     */
    public function postByYearAction($annee)
    {

        $postRepository = $this->getDoctrine()->getRepository("AppBundle:Post");
        $posts = $postRepository->getPostByYear($annee);

        return $this->render(":default:theme.html.twig", [
            "title" => "Liste des posts par année ({$annee})",
            "postList" => $posts,
            "themeParAnnee"=> "on est sur la page des posts par années."
        ]);
    }


    /**
     * @Route("/post/new/{slug}", name="post_new")
     * @param Request $request
     * @param Theme $slug
     * @return Response
     */
    public function newPostAction(Request $request, $slug)
    {

        $themeRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");
        /* @var $slug Theme */
        $theme = $themeRepository->findOneBySlug($slug);

        //génération des nouveaux posts
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        $formView = null;

        if (in_array("ROLE_AUTHOR", $roles)) {
            //génération du formulaire
            $post = new Post();
            dump($user);
            $post->setAuthor($user);
            $post->setTheme($theme);
            $post->setCreatedAt(new \DateTime());
            //$form = $this->createForm(PostType::class, $post);

            //hydratation de l'entité
            //$form->handleRequest($request);

            //on utilise notre service
            $formHandler = $this->get("post.form_handler")->setPost($post);


            //traitement du formulaire
          //  if ($form->isSubmitted() and $form->isValid()) {
            //on utilise notre service
            if ($formHandler->process()) {
                //  if ($post->getImageFileName()) {
                //    $uploadManager = $this->get("stof_doctrine_extensions.uploadable.manager");
                //  $uploadManager->markEntityToUpload($post, $post->getImageFileName());
                //}

                //$em = $this->getDoctrine()->getManager();
                //$em->persist($post);
                //$em->flush();
                //utilisation de mon service
                // $this->get("post.manager")->setPost($post)->save();
                return $this->redirectToRoute("theme_details", ["slug" => $theme->getSlug()]);
            }
            //$formView = $form->createView();
            //on utilise le service
            $formView = $formHandler->getFormView();
        }
        //Fin génération des nouveaux posts
        return $this->render('default/theme.html.twig', [
            "theme" => $theme,
            "postList" => $theme->getPosts(),
            "postForm" => $formView, //on retourne une vue du formulaire
            "titreCreation" => "Nouveau post"
        ]);

    }


    /**
     * @Route("/post/modif/{slug}", name="post_edit")
     * @param Request $request
     * @param Post $slug
     * @return Response
     */
    public function editPostAction(Request $request, $slug)
    {

        $postRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");
        /* @var $slug Post */
        $post = $postRepository->findOneBySlug($slug);

        $theme = $post->getTheme();

        //sécurité de l'opération
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        $userId = isset($user) ? $user->getId() : null;
        if (!in_array("ROLE_AUTHOR", $roles) || $userId != $post->getAuthor()->getId()) {
            throw new AccessDeniedException("Vous n'avez pas les droits pour modifier ce post.");
        }

        //création du formulaire
        $form = $this->createForm(PostType::class, $post);

        //hydratation de l'entité
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            if ($post->getImageFileName()) {
                $uploadManager = $this->get("stof_doctrine_extensions.uploadable.manager");
                $uploadManager->markEntityToUpload($post, $post->getImageFileName());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute("theme_details", ["slug" => $post->getTheme()->getSlug()]);
        }

        return $this->render(":default:theme.html.twig", [
            "theme" => $theme,
            "postList" => $theme->getPosts(),
            "postForm" => $form->createView(), //on retourne une vue du formulaire
            "titreModif" => "Modification de post"
        ]);
    }
}