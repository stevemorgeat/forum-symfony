<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Vote;
use AppBundle\Form\AnswerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Post;

class AnswerController extends Controller
{

    /**
     * @Route("/answer/new/{slug}", name="answer_new")
     * @param Request $request
     * @param Post $slug
     * @return Response
     */
    public function newAnswerAction(Request $request, $slug)
    {

        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");

        /* @var $post Post */
        $post = $repository->findOneBySlug($slug);
        $answerRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Answer");
        $answers = $answerRepository->getAnswerVote($post->getId());

        //génération des nouveaux answer
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        $formView = null;

        if (in_array("ROLE_AUTHOR", $roles)) {
            //génération du formulaire
            $answer = new Answer();
            $answer->setAuthor($user);
            $answer->setPost($post);
            $answer->setCreatedAt(new \DateTime());
            $form = $this->createForm(AnswerType::class, $answer);

            //hydratation de l'entité
            $form->handleRequest($request);

            //traitement du formulaire
            if ($form->isSubmitted() and $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($answer);
                $em->flush();

                return $this->redirectToRoute('post_details',
                    ["slug" => $post->getSlug()]);
            }
            $formView = $form->createView();
        }

        return $this->render('post/details.html.twig', [
            "titreAnswer" => "Nouvelle réponse",
            "post" => $post,
            "answerList" => $answers,
            "newAnswerForm" => $formView, //on retourne une vue du formulaire
        ]);

    }


    /**
     * @Route("/answer/modif/{id}", name="answer_edit")
     * @param Request $request
     * @param Answer $answer
     * @return Response
     */
    public function editAnswerAction(Request $request, Answer $answer)
    {
        $post = $answer->getPost();
        $answerRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Answer");
        $answers = $answerRepository->getAnswerVote($post->getId());


        //sécurité de l'opération
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        $userId = isset($user) ? $user->getId() : null;

        if (!in_array("ROLE_AUTHOR", $roles) || $userId != $answer->getAuthor()->getId()) {
            throw new AccessDeniedException("Vous n'avez pas les droits pour modifier ce post.");
        }

        //création du formulaire
        $form = $this->createForm(AnswerType::class, $answer);

        //hydratation de l'entité
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush();

            return $this->redirectToRoute("post_details",
                ["slug" => $post->getSlug()]);
        }

        return $this->render("post/details.html.twig", [
            "titreAnswer" => "Edit de votre réponse",
            "post" => $post,
            "answerList" => $answers,
            "editAnswerForm" => $form->createView() //on retourne une vue du formulaire
        ]);
    }


    /**
     * @Route("/answer/vote/{id}/{voteUser}", name="vote_plus")
     * @param Answer $answer
     * @param $voteUser
     * @return Response
     */
    public function AddVote(Answer $answer, $voteUser)
    {

        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        $userId = isset($user) ? $user->getId() : null;
        if (in_array("ROLE_AUTHOR", $roles)) {
            $voteRepository = $this->getDoctrine()
                ->getRepository("AppBundle:Vote");
            $resultatVote = $voteRepository->getVoteByAuthorAndbyAnswer($answer->getId(), $userId);
            dump($resultatVote);
            $voteEnregistre = $resultatVote[0]["vote"];
            $voteEnregistreId = $resultatVote[0]["id"];
            dump($voteEnregistreId);
            dump($voteEnregistre);
            dump((int)$voteUser);
            if ($resultatVote !== null) {
                if ($voteEnregistre == intval($voteUser)) {
                    $messageVote = "Vous avez déjà effectué ce vote.";
                } else {

                    $vote = $voteRepository->find($voteEnregistreId);
                    $vote->setVote((int)$voteUser);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($vote);
                    $em->flush();
                    $messageVote = "Votre vote a été modifié.";

                }
            } else {
                $vote = new Vote();
                $vote->setAuthor($user)->setAnswer($answer)->setVote((int)$voteUser);
                $em = $this->getDoctrine()->getManager();
                $em->persist($vote);
                $em->flush();
                $messageVote = "Merci pour votre vote.";
            }
        }else{
            $messageVote= "Vous devrez être connectés pour effectuer un vote!";
        }
        $post = $answer->getPost();
        $answerRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Answer");
        $answers = $answerRepository->getAnswerVote($post->getId());


        return $this->render("post/details.html.twig", [
            "post" => $post,
            "answerList" => $answers,
            "messageVote" => $messageVote
        ]);
    }
}
