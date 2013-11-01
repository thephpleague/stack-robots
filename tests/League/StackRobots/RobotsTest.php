<?php

namespace League\StackRobots;

use Symfony\Component\HttpFoundation\Response;

putenv('SERVER_ENV=production');

class RobotsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * The object to be tested.
	 *
	 * @var Robots
	 */
	protected $fixture;

	/**
	 * Setup the tests.
	 *
	 * @return void
	 */
	public function setUp()
	{
		$mockApp = $this->getMock('Symfony\\Component\\HttpKernel\\HttpKernelInterface');

		$this->fixture = new Robots($mockApp);
	}

	/**
	 * The app property should be an instanceof HttpKernelInterface
	 *
	 * @return void
	 */
	public function testConstructPassesApp()
	{
		$this->assertAttributeInstanceOf(
			'Symfony\\Component\\HttpKernel\\HttpKernelInterface',
			'app',
			$this->fixture,
			'The app property should be an instanceof HttpKernelInterface'
		);
	}

	/**
	 * The env property should be set to the value set in the constructor.
	 *
	 * @return void
	 */
	public function testConstructDefaultEnv()
	{
		$this->assertAttributeEquals(
			'production',
			'env',
			$this->fixture,
			'The env property should be set to the value set in the constructor.'
		);
	}

	/**
	 * The envVar property should be set to the value set in the constructor.
	 *
	 * @return void
	 */
	public function testConstructDefaultEnvVar()
	{
		$this->assertAttributeEquals(
			'SERVER_ENV',
			'envVar',
			$this->fixture,
			'The envVar property should be set to the value set in the constructor.'
		);
	}

	/**
	 * The env property should be set to the value passed to the constructor.
	 *
	 * @return void
	 */
	public function testConstructPassesEnv()
	{
		$mockApp = $this->getMock('Symfony\\Component\\HttpKernel\\HttpKernelInterface');

		$this->fixture = new Robots($mockApp, 'testing');

		$this->assertAttributeEquals(
			'testing',
			'env',
			$this->fixture,
			'The env property should be set to the value passed to the constructor.'
		);
	}

	/**
	 * The envVar property should be set to the value passed to the constructor.
	 *
	 * @return void
	 */
	public function testConstructPassesEnvVar()
	{
		$mockApp = $this->getMock('Symfony\\Component\\HttpKernel\\HttpKernelInterface');

		$this->fixture = new Robots($mockApp, 'testing', 'MY_ENVVAR');

		$this->assertAttributeEquals(
			'MY_ENVVAR',
			'envVar',
			$this->fixture,
			'The envVar property should be set to the value passed to the constructor.'
		);
	}

	/**
	 * Test a request for robots.txt sends the proper response.
	 *
	 * @return void
	 */
	public function testHandleSendsResponse()
	{
		$mockApp = $this->getMock('Symfony\\Component\\HttpKernel\\HttpKernelInterface');
		$mockRequest = $this->getMock('Symfony\Component\HttpFoundation\Request', array('getPathInfo'));

		$mockRequest->expects($this->once())
			->method('getPathInfo')
			->will($this->returnValue('/robots.txt'));

		$this->fixture = new Robots($mockApp, 'testing');

		$response = $this->fixture->handle($mockRequest);

		$expectedResponse = new Response("User-Agent: *\nDisallow: /", 200, array('Content-Type' => 'text/plain'));

		$this->assertEquals(
			$expectedResponse,
			$response,
			'When requesting the robots.txt file, the proper response should be received.'
		);
	}

	/**
	 * Test a typical handling of a request.
	 *
	 * @return void
	 */
	public function testHandle()
	{
		$mockApp = $this->getMock('Symfony\\Component\\HttpKernel\\HttpKernelInterface', array('handle'));
		$mockRequest = $this->getMock('Symfony\Component\HttpFoundation\Request', array('getPathInfo'));


		$mockApp->expects($this->once())
			->method('handle')
			->will($this->returnValue('foo'));

		$this->fixture = new Robots($mockApp, 'testing');

		$this->assertEquals(
			'foo',
			$this->fixture->handle($mockRequest),
			'When in the production environment, the handler should be passed on to the next middleware.'
		);
	}
}
