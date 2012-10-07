<?php

namespace Courtyard\Forum\Repository;

interface BoardRepositoryInterface
{
    function find($id);
    function findAll();
    function findBySlug($slug);
}