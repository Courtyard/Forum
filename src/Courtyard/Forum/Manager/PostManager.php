<?php

namespace Courtyard\Forum\Manager;

use Courtyard\Forum\Entity\Post;
use Courtyard\Forum\Entity\PostStatuses;
use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Event\PostEvent;

class PostManager extends ObjectManager
{
    /**
     * Creates a new Post
     * @param    \Courtyard\Forum\Entity\Post
     */
    public function create($post)
    {
        $this->assertType($post);
        $post->setStatus(PostStatuses::STATUS_PUBLISHED);
        $post->setNumber($post->getTopic()->getPostLast()->getNumber() + 1);
        
        try {
            $this->em->getConnection()->beginTransaction();

            $this->dispatcher->dispatch(ForumEvents::POST_CREATE_PRE, new PostEvent($post));
            
            $this->em->persist($post);
            $this->em->flush();
            
            $this->dispatcher->dispatch(ForumEvents::POST_CREATE_POST, new PostEvent($post));
            
            $this->em->getConnection()->commit();

        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack(); 
            throw $e;
        }
    }
    
    /**
     * Updates an existing Post
     * @param    \Courtyard\Forum\Entity\Post
     */
    public function update($post)
    {
        $this->assertType($post);
        
        $this->dispatcher->dispatch(ForumEvents::POST_UPDATE_PRE, new PostEvent($post));
        
        $this->em->persist($post);
        $this->em->flush();
        
        $this->dispatcher->dispatch(ForumEvents::POST_UPDATE_POST, new PostEvent($post));
    }
    
    /**
     * Removes a Post
     * @param    \Courtyard\Forum\Entity\Post
     */
    public function delete($post)
    {
        $this->assertType($post);
        
        $this->dispatcher->dispatch(ForumEvents::POST_DELETE_PRE, new PostEvent($post));
        
        $this->em->remove($post);
        $this->em->flush();
        
        $this->dispatcher->dispatch(ForumEvents::POST_DELETE_POST, new PostEvent($post));
    }
    
    protected function getType()
    {
        return 'Courtyard\Forum\Entity\Post';
    }
}