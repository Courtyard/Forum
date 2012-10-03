<?php

namespace Courtyard\Forum\Entity;

class Post implements PostInterface
{
    /**
     * @var integer $id
     */
    protected $id;
    
    /**
     * @var integer $number
     */
    protected $number;
    
    /**
     * @var string $title
     */
    protected $title;
    
    /**
     * @var string $message
     */
    protected $message;
    
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
    protected $status = PostStatuses::STATUS_DRAFT;
    
    /**
     * @var Courtyard\Forum\Entity\TopicInterface
     */
    protected $topic;
    
    /**
     * @var Courtyard\Forum\Entity\UserInterface
     */
    protected $author;
    
    
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
     * Set number
     *
     * @param integer $number
     * @return Post
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }
    
    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }
    
    /**
     * Set title
     *
     * @param string $title
     * @return Post
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
     * Set message
     *
     * @param string $message
     * @return Post
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }
    
    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Set datePosted
     *
     * @param \DateTime $datePosted
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * Set topic
     *
     * @param Courtyard\Forum\Entity\TopicInterface $topic
     * @return Post
     */
    public function setTopic(TopicInterface $topic = null)
    {
        $this->topic = $topic;
    
        return $this;
    }
    
    /**
     * Get topic
     *
     * @return Courtyard\Forum\Entity\TopicInterface
     */
    public function getTopic()
    {
        return $this->topic;
    }
    
    /**
     * Set author
     *
     * @param Courtyard\Forum\Entity\UserInterface $author
     * @return Post
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
}