<?php

namespace Courtyard\Forum\Entity;

interface PostInterface
{
    function setTopic(TopicInterface $topic);
    function getTopic();
    function getTitle();
    function getMessage();
    function getNumber();
}