<?php

namespace Turbo\Interfaces\Middleware;

interface Middleware
{
	//each pipe enter
	public function handle($passable, $stack);
}
