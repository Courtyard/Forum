<?php

namespace Courtyard\Forum\Tests\Controller;

use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Controller\PostsController;
use Courtyard\Forum\Templating\TemplateReference;

class PostsControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetEditPost()
    {
        $post = $this->getPost();

        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface', array('create'));
        $formFactory
            ->expects($this->once())
            ->method('create')
            ->with('forum_post_edit', $post)
            ->will($this->returnValue($this->getForm()))
        ;

        $controller = $this->getController(null, null, null, null, $formFactory);

        $response = $controller->editAction($post);

        $this->assertInstanceOf('Courtyard\Forum\Templating\TemplateResponse', $response);
        $this->assertEquals(new TemplateReference('Posts', 'edit'), $response->getTemplate());
        $this->assertSame($post, $response->getVariable('post'));
        $this->assertEquals(ForumEvents::VIEW_POST_EDIT, $response->getEvent());
    }

    protected function getPost()
    {
        $board = $this->getMock('Courtyard\Forum\Entity\BoardInterface');

        $topic = $this->getMock('Courtyard\Forum\Entity\TopicInterface');
        $topic
            ->expects($this->once())
            ->method('getBoard')
            ->will($this->returnValue($board))
        ;

        $post = $this->getMock('Courtyard\Forum\Entity\PostInterface');
        $post
            ->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue($topic))
        ;

        return $post;
    }

    protected function getController($request = null, $session = null, $generator = null, $container = null, $formFactory = null)
    {
        if (!$request) {
            $request = $this->getMock('Symfony\Component\HttpFoundation\Request', array('getMethod'));
            $request
                ->expects($this->any())
                ->method('getMethod')
                ->will($this->returnValue('GET'))
            ;
        }

        return new PostsController(
            $request,
            $session ?: $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface'),
            $generator ?: $this->getMock('Courtyard\Forum\Router\ForumUrlGeneratorInterface'),
            $container ?: $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface'),
            $formFactory ?: $this->getMock('Symfony\Component\Form\FormFactoryInterface')
        ); 
    }
    
    protected function getForm()
    {
        $form = $this->getMock('Symfony\Component\Form\Tests\FormInterface', array('createView'));
        $form
            ->expects($this->once())
            ->method('createView')
            ->will($this->returnValue($this->getFormView()))
        ;

        return $form;
    }

    protected function getFormView()
    {
        return $this->getMockBuilder('Symfony\Component\Form\FormView')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}