<?php

/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/07/2015
 * Time: 21:15
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{
	public function testBasic()
	{
		$cmd = new Ark4ne\Process\Command\Command('php');

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals([], $cmd->getArguments());
		$this->assertEquals([], $cmd->getOptions());
	}

	public function testArgumentString()
	{
		$cmd = new Ark4ne\Process\Command\Command('php', __DIR__ . '/command/basic.php');

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals(1, count($cmd->getArguments()));
		$this->assertEquals(
			[md5(__DIR__ . '/command/basic.php') => __DIR__ . '/command/basic.php']
			,
			$cmd->getArguments());
		$this->assertEquals([], $cmd->getOptions());
	}

	public function testArgumentArray()
	{
		$cmd = new Ark4ne\Process\Command\Command('php', [__DIR__ . '/command/basic.php']);

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals(1, count($cmd->getArguments()));
		$this->assertEquals(
			[md5(__DIR__ . '/command/basic.php') => __DIR__ . '/command/basic.php']
			,
			$cmd->getArguments());
		$this->assertEquals([], $cmd->getOptions());
	}

	public function testOptionsString()
	{
		$cmd = new Ark4ne\Process\Command\Command('php', null, 'option=opt');

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals(0, count($cmd->getArguments()));
		$this->assertEquals(1, count($cmd->getOptions()));
		$this->assertEquals([md5('option=opt') => 'option=opt'], $cmd->getOptions());
	}

	public function testOptionsArray()
	{
		$cmd = new Ark4ne\Process\Command\Command('php', null, ['option=opt']);

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals(0, count($cmd->getArguments()));
		$this->assertEquals(1, count($cmd->getOptions()));
		$this->assertEquals([md5('option=opt') => 'option=opt'], $cmd->getOptions());
	}
}