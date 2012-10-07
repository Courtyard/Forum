<?php

namespace Courtyard\Forum\Controller;

use Courtyard\Forum\Manager\ObjectManagerInterface;
use Courtyard\Forum\Router\ForumUrlGeneratorInterface;
use Courtyard\Forum\Repository\BoardRepositoryInterface;
use Courtyard\Forum\Repository\TopicRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PublicController
{
    protected $request;
    protected $session;
    protected $router;
    protected $container;
    protected $formFactory;

    protected $topicRepository;
    protected $postRepository;
    protected $boardRepository;

    public function __construct(Request $request, SessionInterface $session, ForumUrlGeneratorInterface $router, ContainerInterface $container, FormFactoryInterface $formFactory)
    {
        $this->request = $request;
        $this->session = $session;
        $this->router  = $router;
        $this->container = $container;
        $this->formFactory = $formFactory;
    }

    public function setObjectManager(ObjectManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function setTopicRepository(TopicRepositoryInterface $repository)
    {
        $this->topicRepository = $repository;
    }

    public function setPostRepository(EntityRepository $repository)
    {
        $this->postRepository = $repository;
    }

    public function setBoardRepository(BoardRepositoryInterface $repository)
    {
        $this->boardRepository = $repository;
    }
}