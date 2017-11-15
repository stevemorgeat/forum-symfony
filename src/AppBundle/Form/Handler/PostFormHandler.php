<?php

namespace AppBundle\Form\Handler;


use AppBundle\Entity\Manager\PostManager;
use AppBundle\Entity\Post;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;

class PostFormHandler
{

    /**
     * @var Post
     */
    private $post;

    /**
     * @var string
     */
    private $formClassName;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var PostManager
     */
    private $manager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var UploadableManager
     */
    private $uploadManager;

    /**
     * PostFormHandler constructor.
     * @param Post $post
     * @param string $formClassName
     * @param FormFactory $formFactory
     * @param PostManager $manager
     * @param RequestStack $requestStack
     * @param UploadableManager $uploadManager
     */
    public function __construct(Post $post,
                                $formClassName,
                                FormFactory $formFactory,
                                PostManager $manager,
                                RequestStack $requestStack,
                                UploadableManager $uploadManager)
    {
        $this->post = $post;
        $this->formClassName = $formClassName;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->requestStack = $requestStack;
        $this->uploadManager = $uploadManager;
    }

    public function process()
    {

        $this->form = $this->formFactory->create($this->formClassName, $this->post);
        $this->form->handleRequest($this->requestStack->getCurrentRequest());

        $success = false;

        if ($this->form->isSubmitted() and $this->form->isValid()) {
            $success = true;
            if ($this->post->getImageFileName() instanceof UploadedFile) {
                $this->uploadManager->markEntityToUpload(
                    $this->post,
                    $this->post->getImageFileName()
                );
            }
            $this->manager->setPost($this->post)->save();
        }

        return $success;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post $post
     * @return PostFormHandler
     */
    public function setPost($post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Form $form
     * @return PostFormHandler
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }


    public function getFormView()
    {
        return $this->form->createView();
    }

}