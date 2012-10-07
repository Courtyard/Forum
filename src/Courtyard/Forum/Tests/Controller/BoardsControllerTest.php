<?php

namespace Courtyard\Forum\Tests\Controller;

use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Controller\BoardsController;
use Courtyard\Forum\Templating\TemplateReference;

class BoardsControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testIndexAction()
    {
        $controller = $this->getController();

        $repo = $this->getMock('Courtyard\Forum\Repository\BoardRepositoryInterface');
        $repo
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($boards = array($this->getMock('Courtyard\Forum\Entity\BoardInterface'))))
        ;

        $controller->setBoardRepository($repo);

        $response = $controller->indexAction();

        $this->assertInstanceOf('Courtyard\Forum\Templating\TemplateResponse', $response);
        $this->assertEquals(new TemplateReference('Boards', 'index'), $response->getTemplate());
        $this->assertSame($boards, $response->getVariable('boards'));
        $this->assertEquals(ForumEvents::VIEW_FORUMS, $response->getEvent());
    }

    public function testViewAction()
    {
        $controller = $this->getController();

        $repo = $this->getMock('Courtyard\Forum\Repository\TopicRepositoryInterface');
        $repo
            ->expects($this->once())
            ->method('findByBoard')
            ->will($this->returnValue($topics = array($this->getMock('Courtyard\Forum\Entity\TopicInterface'))))
        ;

        $controller->setTopicRepository($repo);
        $board = $this->getMock('Courtyard\Forum\Entity\BoardInterface');

        $response = $controller->viewAction($board);

        $this->assertInstanceOf('Courtyard\Forum\Templating\TemplateResponse', $response);
        $this->assertEquals(new TemplateReference('Boards', 'view'), $response->getTemplate());
        $this->assertSame($topics, $response->getVariable('topics'));
        $this->assertEquals(ForumEvents::VIEW_BOARD, $response->getEvent());
    }

    protected function getController()
    {
        return new BoardsController(
            $this->getMock('Symfony\Component\HttpFoundation\Request'),
            $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface'),
            $this->getMock('Courtyard\Forum\Router\ForumUrlGeneratorInterface'),
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface'),
            $this->getMock('Symfony\Component\Form\FormFactoryInterface')
        ); 
    }
}