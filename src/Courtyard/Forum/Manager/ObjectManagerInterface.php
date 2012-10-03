<?php

namespace Courtyard\Forum\Manager;

interface ObjectManagerInterface
{
    function create($obj);
    
    function update($obj);
    
    function delete($obj);
}