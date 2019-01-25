<?php

namespace Egysatria\Middleware;

class Middleware
{
	protected $container;

	public function __construct( $container )
	{
		$this->container = $container;
	}
}