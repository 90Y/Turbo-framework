<?php

/**
 * Container
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Container
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

namespace Turbo\Container;

use Closure;
use ArrayAccess;
use ReflectionClass;
use ReflectionParameter;
use Turbo\Exception\TurboException as Exception;
use Turbo\Interfaces\Container\Container as iContainer;

class Container implements ArrayAccess, iContainer
{
    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * @var array
     */
    protected $binds = [];

    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * 这是对象别名
     * @param  string $abstract
     * @param  string $alias
     * @return void
     */
    public function alias($abstract, $alias)
    {
        $this->aliases[$alias] = $this->normalize($abstract);
    }

    /**
     * @param $abstract
     * @param $concrete
     */
    public function bind($abstract, $concrete)
    {

        $abstract = $this->normalize($abstract);

        $concrete = $this->normalize($concrete);

        if (is_array($abstract)) {
            list($abstract, $alias) = $this->extractAlias($abstract);
            $this->alias($abstract, $alias);
        }

        if ($concrete instanceof Closure) {
            $this->binds[$abstract] = $concrete;
        } else {
            $this->settings[$abstract] = $concrete;
        }
    }

    /**
     * 实例创建
     * @param $abstract
     * @param array       $parameters
     */
    public function build($abstract, array $parameters = [])
    {
        $reflector = new ReflectionClass($abstract);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$abstract} is not instantiable");
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        $dependencies = $constructor->getParameters();
        $instances    = $this->getDependencies(
            $dependencies, $parameters
        );

        $instance = $reflector->newInstanceArgs($instances);

        $this->instances[$abstract] = $instance;

        return $instance;
    }

    /**
     * 存储实例
     * @param $abstract
     * @param $concrete
     */
    public function instance($abstract, $concrete)
    {

        $this->instances[$abstract] = $concrete;
        //$this->bind($abstract, $concrete);
        //$this->instances[$abstract] = $instance;
    }

    /**
     * @param  $abstract
     * @param  array       $parameters
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        $abstract = $this->getAlias($this->normalize($abstract));

        if (isset($this->binds[$abstract])) {
            array_unshift($parameters, $this);

            return call_user_func_array($this->binds[$abstract], $parameters);
        }

        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (!isset($this->settings[$abstract])) {
            return null;
        }

        $abstract = $this->settings[$abstract];

        if ($abstract instanceof Closure) {
            array_unshift($parameters, $this);

            return call_user_func_array($this->binds[$abstract], $parameters);
        }

        return $this->build($abstract, $parameters);
    }

    /**
     * @param $offset
     */
    public function offsetExists($offset)
    {
        return isset($this->instances[$offset]);
    }

    /**
     * @param $offset
     */
    public function offsetGet($offset)
    {
        return isset($this->instances[$offset]) ? $this->instances[$offset] : null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function offsetSet($key, $value)
    {
        $this->bind($key, $value);
    }

    /**
     * @param $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->instances[$offset]);
    }

    /**
     * 存储单例
     * @param $abstract
     * @param array       $parameters
     */
    public function singleton($abstract, array $parameters = []) {}

    /**
     * @param array $definition
     */
    protected function extractAlias(array $definition)
    {
        return [key($definition), current($definition)];
    }

    /**
     *  获取别名
     * @param  string   $abstract
     * @return string
     */
    protected function getAlias($abstract)
    {
        if (!isset($this->aliases[$abstract])) {
            return $abstract;
        }

        return $this->getAlias($this->aliases[$abstract]);
    }

    /**
     * @param array $parameters
     * @param array $primitives
     */
    protected function getDependencies(array $parameters, array $primitives = [])
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {

            $dependency = $parameter->getClass();
            if ($dependency === null) {
                throw new \Exception("Can not be resolve class dependency {$parameter->name}");
            } else {
                $dependencies[] = $this->resolveClass($parameter);
            }
        }

        return $dependencies;
    }

    /**
     * 格式化类名称
     * @param  string   $service
     * @return string
     */
    protected function normalize($service)
    {
        return is_string($service) ? ltrim($service, '\\') : $service;
    }

    /**
     * @param  ReflectionParameter $parameter
     * @return mixed
     */
    protected function resolveClass(ReflectionParameter $parameter)
    {
        try {
            return $this->make($parameter->getClass()->name);
        } catch (Exception $e) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }
            throw $e;
        }
    }
}
