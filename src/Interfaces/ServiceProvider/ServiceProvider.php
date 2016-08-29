<?php

namespace Turbo\Interfaces\ServiceProvider;

use Turbo\Container\Container;

interface ServiceProvider
{
    public function register(Container $app);

    public function unregister(Container $app);

    public function terminal(Container $app);
}
