<?php

namespace Courtyard\Forum\Entity;

interface PostInterface
{
    function getTopic();
    function getTitle();
    function getMessage();
}