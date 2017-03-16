<?php

namespace Turbo\Interfaces\Support\Service;

interface Service
{
    public function model($modelName);

    public function sibling($serviceName);
}
