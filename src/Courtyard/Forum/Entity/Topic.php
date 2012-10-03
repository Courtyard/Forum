<?php

namespace Courtyard\Forum\Entity;

class Topic implements TopicInterface
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var \DateTime $datePosted
     */
    protected $datePosted;

    /**
     * @var \DateTime $dateUpdated
     */
    protected $dateUpdated;

    /**
     * @var integer $status
     */
    protected $status = TopicStatuses::STATUS_DRAFT;

    /**
     * @var integer $views
     */
    protected $views = 0;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $posts;

    /**
     * @var Courtyard\Forum\Entity\Board
     */
    protected $board;

    /**
     * @var UserInterface
     */
    protected $author;

    /**
     * @var Courtyard\Forum\Entity\PostInterface
     */
    protected $postFirst;

    /**
     * @var Courtyard\Forum\Entity\PostInterface
     */
    protected $postLast;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Topic
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set datePosted
     *
     * @param \DateTime $datePosted
     * @return Topic
     */
    public function setDatePosted($datePosted)
    {
        $this->datePosted = $datePosted;
    
        return $this;
    }

    /**
     * Get datePosted
     *
     * @return \DateTime 
     */
    public function getDatePosted()
    {
        return $this->datePosted;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return Topic
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    
        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime 
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Topic
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return Topic
     */
    public function setViews($views)
    {
        $this->views = $views;
    
        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Add post
     *
     * @param Courtyard\Forum\Entity\Post $post
     * @return Topic
     */
    public function addPost(PostInterface $post)
    {
        $post->setTopic($this);
        $this->posts[] = $post;
    
        return $this;
    }

    /**
     * Remove posts
     *
     * @param Courtyard\Forum\Entity\Post $posts
     */
    public function removePost(PostInterface $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set board
     *
     * @param Courtyard\Forum\Entity\BoardInterface $board
     * @return Topic
     */
    public function setBoard(BoardInterface $board = null)
    {
        $this->board = $board;
        return $this;
    }

    /**
     * Get board
     *
     * @return Courtyard\Forum\Entity\Board 
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set author
     *
     * @param Courtyard\Forum\Entity\UserInterface $author
     * @return Topic
     */
    public function setAuthor(UserInterface $author = null)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     *
     * @return Courtyard\Forum\Entity\UserInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set postFirst
     *
     * @param Courtyard\Forum\Entity\PostInterface $post
     * @return Topic
     */
    public function setPostFirst(PostInterface $post = null)
    {
        $this->postFirst = $post;
        $this->postLast  = $post;
        $this->addPost($post);
        
        return $this;
    }

    /**
     * Get postFirst
     *
     * @return Courtyard\Forum\Entity\PostInterface
     */
    public function getPostFirst()
    {
        return $this->postFirst;
    }

    /**
     * Set postLast
     *
     * @param Courtyard\Forum\Entity\PostInterface $postLast
     * @return Topic
     */
    public function setPostLast(PostInterface $postLast = null)
    {
        $this->postLast = $postLast;
    
        return $this;
    }

    /**
     * Get postLast
     *
     * @return Courtyard\Forum\Entity\PostInterface
     */
    public function getPostLast()
    {
        return $this->postLast;
    }
}