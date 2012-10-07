<?php

namespace Courtyard\Forum\Controller;

use Courtyard\Forum\Entity\TopicInterface;
use Courtyard\Forum\Entity\PostInterface;
use Courtyard\Forum\Manager\ObjectManagerInterface;
use Courtyard\Forum\Templating\TemplateResponse;
use Courtyard\Forum\Templating\TemplateReference;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PostsController extends PublicController
{
    /**
     * View a specific post
     * 
     * @param    Courtyard\Forum\Entity\PostInterface
     * @return   Courtyard\Forum\Templating\TemplateResponse
     */
    public function viewAction(PostInterface $post)
    {
        return new TemplateResponse(
            new TemplateReference('Topics', 'list'),
            array(
                'board'  => $board = $post->getTopic()->getBoard(),
                'topics' => $this->topicRepository->findLatestIn($board)
            ),
            ForumEvents::VIEW_POST
        );
    }

    /**
     * Reply to a topic
     * 
     * @param    Courtyard\Forum\Entity\TopicInterface
     * @return   TemplateResponse or RedirectResponse on completion
     */
    public function replyAction(TopicInterface $topic)
    {
        $reply = $this->manager->create($topic);
        $form = $this->formFactory->create('forum_reply_inline', $reply);

        if ($this->request->getMethod() == 'POST') {
            $form->bindRequest($this->request);

            if ($form->isValid()) {
                $this->manager->persist($reply);
                $this->session->getFlashBag()->add('success', 'Message posted successfully.');
                return new RedirectResponse($this->router->generatePostUrl($reply));
            }
        }

        return new TemplateResponse(
            new TemplateReference('Posts', 'reply'),
            array(
                'topic' => $topic,
                'board' => $topic->getBoard(),
                'posts' => $this->postRepository->findAllByTopic($topic)
            ),
            ForumEvents::VIEW_TOPIC_REPLY
        );
    }

    /**
     * Edit to a specific Post
     * 
     * @param    Courtyard\Forum\Entity\PostInterface
     * @return   TemplateResponse or RedirectResponse on completion
     */
    public function editAction(PostInterface $post)
    {
        throw new \Exception('Posts/edit is not implemented yet');
    }

    /**
     * Delete a specific Post
     * 
     * @param    Courtyard\Forum\Entity\PostInterface
     * @return   TemplateResponse or RedirectResponse on completion
     */
    public function deleteAction(PostInterface $post)
    {
        throw new \Exception('Posts/edit is not implemented yet');
    }

    /**
     * Bring the relevant ObjectManager into scope to save Topics
     * @param    Courtyard\Forum\Manager\ObjectManagerInterface
     */
    public function setPostManager(ObjectManagerInterface $manager)
    {
        $this->manager = $manager;
    }
}