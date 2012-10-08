<?php

namespace Courtyard\Forum\Tests\Manager;

abstract class TransactionManagerTest extends \PHPUnit_Framework_TestCase
{
    protected function getMockDispatcherExpectingEvents(array $firstPass, array $secondPass)
    {
        $dispatcher = $this->getMockBuilder('Courtyard\Forum\Manager\Transaction\TransactionDispatcher')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $dispatcher
            ->expects($this->any())
            ->method('newTransaction')
            ->will($this->returnValue($this->getTransactionExpecting($firstPass, $secondPass)))
        ;

        return $dispatcher;
    }

    protected function getTransactionExpecting(array $firstPass, array $secondPass)
    {
        $transaction = $this->getMock('Courtyard\Forum\Manager\Transaction\TransactionEvents');

        $index = 0;

        foreach ($firstPass as $expectedEvent) {
            $transaction
                ->expects($this->at($index++))
                ->method('addFirstPass')
                ->with($this->equalTo($expectedEvent), $this->isInstanceOf('Courtyard\Forum\Event\ForumEvent'))
                ->will($this->returnValue($transaction))
            ;
        }

        foreach ($secondPass as $expectedEvent) {
            $transaction
                ->expects($this->at($index++))
                ->method('addSecondPass')
                ->with($this->equalTo($expectedEvent), $this->isInstanceOf('Courtyard\Forum\Event\ForumEvent'))
                ->will($this->returnValue($transaction))
            ;
        }

        return $transaction;
    }
}