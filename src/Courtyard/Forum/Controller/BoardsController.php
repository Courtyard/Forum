<?php

namespace Courtyard\Forum\Controller;

use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Entity\BoardInterface;
use Courtyard\Forum\Templating\TemplateResponse;
use Courtyard\Forum\Templating\TemplateReference;

class BoardsController extends PublicController
{
    /**
     * View all Boards
     * 
     * @return   Courtyard\Forum\Templating\TemplateResponse
     */
    public function indexAction()
    {
        return new TemplateResponse(
            new TemplateReference('Boards', 'index'),
            array('boards' => $this->boardRepository->findAll()),
            ForumEvents::VIEW_FORUMS
        );
    }

    /**
     * View a specific Board, and list all Topics
     * 
     * @param    Courtyard\Forum\Entity\BoardInterface
     * @return   Courtyard\Forum\Templating\TemplateResponse
     */
    public function viewAction(BoardInterface $board)
    {
        return new TemplateResponse(
            new TemplateReference('Boards', 'view'),
            array(
                'board' => $board,
                'topics' => $this->topicRepository->findByBoard($board)
            ), 
            ForumEvents::VIEW_BOARD
        );
    }
}