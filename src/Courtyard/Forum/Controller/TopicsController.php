<?php

namespace Courtyard\Forum\Controller;

use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Entity\BoardInterface;
use Courtyard\Forum\Entity\TopicInterface;
use Courtyard\Forum\Manager\ObjectManagerInterface;
use Courtyard\Forum\Templating\TemplateResponse;
use Courtyard\Forum\Templating\TemplateReference;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TopicsController extends PublicController
{
    /**
     * @var    Courtyard\Forum\Manager\ObjectManagerInterface
     */
    protected $topicManager;

    /**
     * @var    Courtyard\Forum\Manager\ObjectManagerInterface
     */
    protected $postManager;
    
    
    /**
     * List the topics in a Board
     * 
     * @param    Courtyard\Forum\Entity\BoardInterface
     * @return   Courtyard\Forum\Templating\TemplateResponse
     */
    public function listAction(BoardInterface $board)
    {
        return new TemplateResponse(
            new TemplateReference('Topics', 'list'),
            array(
                'board' => $board,
                'topics' => $this->topicRepository->findLatestIn($board)
            ),
            ForumEvents::VIEW_BOARD
        );
    }

    /**
     * Create a new Topic in Board
     * 
     * @param    Courtyard\Forum\Entity\BoardInterface
     * @return   Courtyard\Forum\Templating\TemplateResponse
     */
    public function postAction(BoardInterface $board)
    {
        $topic = $this->topicManager->create($board);
        $form = $this->formFactory->create('forum_topic', $topic);

        if ($this->request->getMethod() == 'POST') {
            $form->bindRequest($this->request);

            if ($form->isValid()) {
                $this->topicManager->persist($topic);
                $this->session->getFlashBag()->add('success', 'Topic posted successfully.');
                return new RedirectResponse($this->router->generateTopicUrl($topic));
            }
        }

        return new TemplateResponse(
            new TemplateReference('Topics', 'post'),
            array(
                'board' => $board,
                'form' => $form->createView()
            ),
            ForumEvents::VIEW_TOPIC_POST
        );
    }

    /**
     * View a Topic
     * 
     * @param    Courtyard\Forum\Entity\TopicInterface
     * @return   Courtyard\Forum\Templating\TemplateResponse
     */
    public function viewAction(TopicInterface $topic)
    {
        return new TemplateResponse(
            new TemplateReference('Topics', 'view'),
            array(
                'topic' => $topic,
                'board' => $topic->getBoard(),
                'posts' => $this->postRepository->findByTopic($topic),
            ),
            ForumEvents::VIEW_TOPIC
        );
    }

    /**
     * @param    Courtyard\Forum\Manager\ObjectManagerInterface
     */
    public function setTopicManager(ObjectManagerInterface $manager)
    {
        $this->topicManager = $manager;
    }
    
    /**
     * @param    Courtyard\Forum\Manager\ObjectManagerInterface
     */
    public function setPostManager(ObjectManagerInterface $manager)
    {
        $this->postManager = $manager;
    }
}