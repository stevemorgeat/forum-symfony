<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use AppBundle\Form\AuthorType;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Theme;
use AppBundle\Entity\Post;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        //on récupère dans la base de donnée les donnée de la table 'theme' grace à l'entité Theme
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");
        //on récupère  dans la base de donnée les donnée de la table 'post' grace à l'entité Post
        $postRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");
        //on applique la methode créée dans le ThemeRepository grace à queryBuilder
        $list = $repository->getAllTheme()->getArrayResult();
        //on applique la methode créée dans le PostRepository grace à queryBuilder
        $postListByYear = $postRepository->getPostsGroupeByYear();

        //on retourne une vue avec des paramètres que l'on pourra réutiliser
        return $this->render('default/index.html.twig',
            ["themeList" => $list, "postList" => $postListByYear]);
    }

    /**
     * @Route("/theme/{slug}", name="theme_details")
     * @param $slug
     * @return Response
     */
    public function themeAction($slug)
    {

        $themeRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");
        /* @var $slug Theme */
        $theme = $themeRepository->findOneBySlug($slug);
        if (!$theme) {
            throw new NotFoundHttpException("Thème introuvable");
        }

        return $this->render('default/theme.html.twig', [
            "theme" => $theme,
            "postList" => $theme->getPosts(),

        ]);
    }



    /**
     * @Route("/inscription", name="author_registration")
     * @param Request $request
     * @return Response
     */
    public function registrationAction(Request $request)
    {
        //on instancie un nouvel auteur
        $author = new Author();
        //on crée le formulaire lié avec l'entité Autheur grace à la variable instanciée
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        //traitement du formulaire
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //Encodage du mot de passe
            $encoderFactory = $this->get("security.encoder_factory");
            $encoder = $encoderFactory->getEncoder($author);
            $author->setPassword($encoder->encodePassword($author->getPlainPassword(), null));
            $author->setPlainPassword(null);

            //Enregistrement dans la base de données
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute("homepage");

        }

        return $this->render("default/author-registration.html.twig", [
            "authorForm" => $form->createView()//createView retourne une vue du formulaire
        ]);
    }

    /**
     * @Route("/author-login", name="author_login")
     * @return Response
     */
    public function author_loginAction()
    {

        $securityUtils = $this->get("security.authentication_utils");
        $lastUserEmail = $securityUtils->getLastUsername();
        $error = $securityUtils->getLastAuthenticationError();

        return $this->render("default/generic-login.html.twig", [
            "action" => $this->generateUrl("author_login_check"),
            "title" => "Login des Auteurs",
            "userName" => $lastUserEmail,
            "error" => $error
        ]);
    }

    /**
     * @Route("/test-service")
     * @return Response
     */
    public function testServiceAction(){
        $helloService= $this->get("service.hello");
        $helloService->setName("Bob");

        $newHelloService = $this->get("service.hello");

        $message = $helloService->sayHello(). ' ' . $newHelloService->sayHello();
        return $this->render("default/test-service.html.twig",["message" => $message]);
    }
}
