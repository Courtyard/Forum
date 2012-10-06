<?php

namespace Courtyard\Forum\Manager;

interface ObjectManagerInterface
{
    function create($obj = null);

    function persist($obj);

    function update($obj);

    function delete($obj);
}