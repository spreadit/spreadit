<?php namespace Functional;

class FunctionalShim
{
	public function __call($method, $args)
	{
		return call_user_func_array('\Functional\\' . $method, $args);
	}
}