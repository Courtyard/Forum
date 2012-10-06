<?php

namespace Courtyard\Forum\Tests\Manager;

use Courtyard\Forum\Manager\Transaction;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testChainableInterface()
    {
        $transaction = new Transaction();
        $this->assertSame($transaction, $transaction->addFirstPass('event.name', $this->getMock('Courtyard\Forum\Event\ForumEvent')));
        $this->assertSame($transaction, $transaction->addSecondPass('event.name', $this->getMock('Courtyard\Forum\Event\ForumEvent')));
    }

    public function testAddAndRetrievePasses()
    {
        $transaction = new Transaction();
        $event = $this->getMock('Courtyard\Forum\Event\ForumEvent');

        $transaction->addFirstPass('event.name', $event);
        $transaction->addSecondPass('event.name.after', clone $event);

        $this->assertEquals(array('event.name' => $event), $transaction->getFirstPass());
        $this->assertEquals(array('event.name.after' => $event), $transaction->getSecondPass());
    }

    public function testDifferentEventInstancesRemainIntact()
    {
        $transaction = new Transaction();

        $beforeEvents = array(
            'on.a.before' => $this->getMock('Courtyard\Forum\Event\ForumEvent'),
            'on.b.before' => $this->getMock('Courtyard\Forum\Event\ForumEvent')
        );

        $afterEvents = array(
            'on.a.after' => $this->getMock('Courtyard\Forum\Event\ForumEvent'),
            'on.b.after' => $this->getMock('Courtyard\Forum\Event\ForumEvent')
        );

        foreach ($beforeEvents as $eventName => $event) {
            $transaction->addFirstPass($eventName, $event);
        }
        foreach ($afterEvents as $eventName => $event) {
            $transaction->addSecondPass($eventName, $event);
        }

        foreach ($transaction->getFirstPass() as $eventName => $event) {
            $this->assertSame($beforeEvents[$eventName], $event);
        }
        foreach ($transaction->getSecondPass() as $eventName => $event) {
            $this->assertSame($afterEvents[$eventName], $event);
        }
    }
    
    public function testEmptyTransactionYieldsEmptyArray()
    {
        $transaction = new Transaction();
        
        $this->assertSame(array(), $transaction->getFirstPass());
        $this->assertSame(array(), $transaction->getSecondPass());
    }
}