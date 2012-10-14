<?php

namespace Courtyard\Forum\Tests\Controller;

use Courtyard\Forum\ForumEvents;
use Courtyard\Forum\Controller\PostsController;
use Courtyard\Forum\Templating\TemplateReference;
use Symfony\Component\HttpFoundation\Request;

class PostsControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testEditResponse()
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
        $this->assertSame($post->getTopic(), $response->getVariable('topic'));
        $this->assertSame($post->getTopic()->getBoard(), $response->getVariable('board'));
        $this->assertEquals(ForumEvents::VIEW_POST_EDIT, $response->getEvent());
    }

    public function testEditPostResponse()
    {
        $post = $this->getPost();

        $request = new Request();
        $request->setMethod('POST');

        $form = $this->getMock('Symfony\Component\Form\Tests\FormInterface', array('createView', 'bindRequest', 'isValid'));
        $form
            ->expects($this->once())
            ->method('bindRequest')
            ->with($request)
        ;
        $form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true))
        ;


        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface', array('create'));
        $formFactory
            ->expects($this->once())
            ->method('create')
            ->with('forum_post_edit', $post)
            ->will($this->returnValue($form))
        ;


        $generator = $this->getMock('Courtyard\Forum\Router\ForumUrlGeneratorInterface');
        $generator
            ->expects($this->once())
            ->method('generatePostUrl')
            ->with($post)
            ->will($this->returnValue('post-url'))
        ;

        $manager = $this->getMock('Courtyard\Forum\Manager\ObjectManagerInterface');
        $manager
            ->expects($this->once())
            ->method('update')
            ->with($post)
        ;

        $flashBag = $this->getMock('Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface');
        $flashBag
            ->expects($this->once())
            ->method('add')
            ->with('success', 'Message updated successfully.')
        ;

        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
            ->disableOriginalConstructor()
            ->setMethods(array('getFlashBag'))
            ->getMock()
        ;
        $session
            ->expects($this->once())
            ->method('getFlashBag')
            ->will($this->returnValue($flashBag))
        ;

        $controller = $this->getController($request, $session, $generator, null, $formFactory);
        $controller->setPostManager($manager);

        $response = $controller->editAction($post);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals('post-url', $response->getTargetUrl());
    }

    public function testDeleteResponse()
    {
        $post = $this->getPost();

        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface', array('create'));
        $formFactory
            ->expects($this->once())
            ->method('create')
            ->with('forum_post_delete', $post)
            ->will($this->returnValue($this->getForm()))
        ;

        $controller = $this->getController(null, null, null, null, $formFactory);

        $response = $controller->deleteAction($post);

        $this->assertInstanceOf('Courtyard\Forum\Templating\TemplateResponse', $response);
        $this->assertEquals(new TemplateReference('Posts', 'delete'), $response->getTemplate());
        $this->assertSame($post, $response->getVariable('post'));
        $this->assertSame($post->getTopic(), $response->getVariable('topic'));
        $this->assertSame($post->getTopic()->getBoard(), $response->getVariable('board'));
        $this->assertEquals(ForumEvents::VIEW_POST_DELETE, $response->getEvent());        
    }
    
    public function testDeletePostResponse()
    {
        $post = $this->getPost();

        $request = new Request();
        $request->setMethod('POST');

        $form = $this->getMock('Symfony\Component\Form\Tests\FormInterface', array('createView', 'bindRequest', 'isValid'));
        $form
            ->expects($this->once())
            ->method('bindRequest')
            ->with($request)
        ;
        $form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true))
        ;


        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface', array('create'));
        $formFactory
            ->expects($this->once())
            ->method('create')
            ->with('forum_post_delete', $post)
            ->will($this->returnValue($form))
        ;


        $generator = $this->getMock('Courtyard\Forum\Router\ForumUrlGeneratorInterface');
        $generator
            ->expects($this->once())
            ->method('generateTopicUrl')
            ->with($post->getTopic())
            ->will($this->returnValue('topic-url'))
        ;

        $manager = $this->getMock('Courtyard\Forum\Manager\ObjectManagerInterface');
        $manager
            ->expects($this->once())
            ->method('delete')
            ->with($post)
        ;

        $flashBag = $this->getMock('Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface');
        $flashBag
            ->expects($this->once())
            ->method('add')
            ->with('success', 'Message deleted successfully.')
        ;

        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
            ->disableOriginalConstructor()
            ->setMethods(array('getFlashBag'))
            ->getMock()
        ;
        $session
            ->expects($this->once())
            ->method('getFlashBag')
            ->will($this->returnValue($flashBag))
        ;

        $controller = $this->getController($request, $session, $generator, null, $formFactory);
        $controller->setPostManager($manager);

        $response = $controller->deleteAction($post);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals('topic-url', $response->getTargetUrl());
    }

    protected function getPost()
    {
        $board = $this->getMock('Courtyard\Forum\Entity\BoardInterface');

        $topic = $this->getMock('Courtyard\Forum\Entity\TopicInterface');
        $topic
            ->expects($this->any())
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