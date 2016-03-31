<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Tests\Controller;

use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolver\ArgumentFromAttributeResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolver\DefaultArgumentValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolver\RequestResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolver\VariadicArgumentValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory;
use Symfony\Component\HttpKernel\Tests\Fixtures\Controller\VariadicController;
use Symfony\Component\HttpFoundation\Request;

class ArgumentResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testGetArguments()
    {
        $factory = new ArgumentMetadataFactory();
        $argumentValueResolvers = array(
            new ArgumentFromAttributeResolver(),
            new VariadicArgumentValueResolver(),
            new RequestResolver(),
            new DefaultArgumentValueResolver(),
        );

        $resolver = new ArgumentResolver($factory, $argumentValueResolvers);

        $request = Request::create('/');
        $controller = array(new self(), 'testGetArguments');
        $this->assertEquals(array(), $resolver->getArguments($request, $controller), '->getArguments() returns an empty array if the method takes no arguments');

        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $controller = array(new self(), 'controllerMethod1');
        $this->assertEquals(array('foo'), $resolver->getArguments($request, $controller), '->getArguments() returns an array of arguments for the controller method');

        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $controller = array(new self(), 'controllerMethod2');
        $this->assertEquals(array('foo', null), $resolver->getArguments($request, $controller), '->getArguments() uses default values if present');

        $request->attributes->set('bar', 'bar');
        $this->assertEquals(array('foo', 'bar'), $resolver->getArguments($request, $controller), '->getArguments() overrides default values if provided in the request attributes');

        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $controller = function ($foo) {};
        $this->assertEquals(array('foo'), $resolver->getArguments($request, $controller));

        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $controller = function ($foo, $bar = 'bar') {};
        $this->assertEquals(array('foo', 'bar'), $resolver->getArguments($request, $controller));

        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $controller = new self();
        $this->assertEquals(array('foo', null), $resolver->getArguments($request, $controller));
        $request->attributes->set('bar', 'bar');
        $this->assertEquals(array('foo', 'bar'), $resolver->getArguments($request, $controller));

        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $request->attributes->set('foobar', 'foobar');
        $controller = 'Symfony\Component\HttpKernel\Tests\Controller\argument_resolver_controller_function';
        $this->assertEquals(array('foo', 'foobar'), $resolver->getArguments($request, $controller));

        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $request->attributes->set('foobar', 'foobar');
        $controller = array(new self(), 'controllerMethod3');

        try {
            $resolver->getArguments($request, $controller);
            $this->fail('->getArguments() throws a \RuntimeException exception if it cannot determine the argument value');
        } catch (\Exception $e) {
            $this->assertInstanceOf('\RuntimeException', $e, '->getArguments() throws a \RuntimeException exception if it cannot determine the argument value');
        }

        $request = Request::create('/');
        $controller = array(new self(), 'controllerMethod5');
        $this->assertEquals(array($request), $resolver->getArguments($request, $controller), '->getArguments() injects the request');
    }

    /**
     * @requires PHP 5.6
     */
    public function testGetVariadicArguments()
    {
        $factory = new ArgumentMetadataFactory();
        $argumentValueResolvers = array(
            new ArgumentFromAttributeResolver(),
            new VariadicArgumentValueResolver(),
            new RequestResolver(),
            new DefaultArgumentValueResolver(),
        );

        $resolver = new ArgumentResolver($factory, $argumentValueResolvers);

        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $request->attributes->set('bar', array('foo', 'bar'));
        $controller = array(new VariadicController(), 'action');
        $this->assertEquals(array('foo', 'foo', 'bar'), $resolver->getArguments($request, $controller));
    }

    /**
     * @requires PHP 5.6
     * @expectedException \InvalidArgumentException
     */
    public function testGetVariadicArgumentsWithoutArrayInRequest()
    {
        $factory = new ArgumentMetadataFactory();
        $argumentValueResolvers = array(
            new ArgumentFromAttributeResolver(),
            new VariadicArgumentValueResolver(),
            new RequestResolver(),
            new DefaultArgumentValueResolver(),
        );

        $resolver = new ArgumentResolver($factory, $argumentValueResolvers);

        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $request->attributes->set('bar', 'foo');
        $controller = array(new VariadicController(), 'action');
        $resolver->getArguments($request, $controller);
    }

    /**
     * @requires PHP 5.6
     * @expectedException \InvalidArgumentException
     */
    public function testGetArgumentWithoutArray()
    {
        $factory = new ArgumentMetadataFactory();
        $valueResolver = $this->getMock(ArgumentValueResolverInterface::class);
        $resolver = new ArgumentResolver($factory, array($valueResolver));

        $valueResolver->expects($this->any())->method('supports')->willReturn(true);
        $valueResolver->expects($this->any())->method('resolve')->willReturn('foo');

        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $request->attributes->set('bar', 'foo');
        $controller = array($this, 'controllerMethod2');
        $resolver->getArguments($request, $controller);
    }

    public function __invoke($foo, $bar = null)
    {
    }

    public function controllerMethod1($foo)
    {
    }

    protected function controllerMethod2($foo, $bar = null)
    {
    }

    protected function controllerMethod3($foo, $bar, $foobar)
    {
    }

    protected static function controllerMethod4()
    {
    }

    protected function controllerMethod5(Request $request)
    {
    }
}

function argument_resolver_controller_function($foo, $foobar)
{
}
