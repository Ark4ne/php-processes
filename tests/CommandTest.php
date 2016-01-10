<?php

use Ark4ne\Processes\Command\Command;

class CommandTest extends \PHPUnit_Framework_TestCase
{
	public function testBasic()
	{
		$cmd = new Command('php');

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals([], $cmd->getArguments());
		$this->assertEquals([], $cmd->getOptions());
	}

	public function testArgumentString()
	{
		$cmd = new Command('php', __DIR__ . '/command/basic.php');

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals(1, count($cmd->getArguments()));
		$this->assertEquals(
			[md5(__DIR__ . '/command/basic.php') => __DIR__ . '/command/basic.php']
			,
			$cmd->getArguments());
		$this->assertEquals([], $cmd->getOptions());

		$this->assertEquals('php ' . __DIR__ . '/command/basic.php', $cmd->getCommandLine());
	}

	public function testArgumentArray()
	{
		$cmd = new Command('php', [__DIR__ . '/command/basic.php']);

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals(1, count($cmd->getArguments()));
		$this->assertEquals(
			[md5(__DIR__ . '/command/basic.php') => __DIR__ . '/command/basic.php']
			,
			$cmd->getArguments());
		$this->assertEquals([], $cmd->getOptions());

		$this->assertEquals('php ' . __DIR__ . '/command/basic.php', $cmd->getCommandLine());
	}

	public function testOptionsString()
	{
		$cmd = new Command('php', null, 'option=opt');

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals(0, count($cmd->getArguments()));
		$this->assertEquals(1, count($cmd->getOptions()));
		$this->assertEquals(['option=opt' => ''], $cmd->getOptions());

		$this->assertEquals('php --option=opt=""', $cmd->getCommandLine());
	}

	public function testOptionsArray()
	{
		$cmd = new Command('php', null, ['option' => 'opt']);

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals(0, count($cmd->getArguments()));
		$this->assertEquals(1, count($cmd->getOptions()));
		$this->assertEquals(['option' => 'opt'], $cmd->getOptions());
		$this->assertEquals('php --option="opt"', $cmd->getCommandLine());
	}

	public function testOptionsType()
	{
		$cmd = new Command('php', null, [
			'option_null'   => null,
			'option_bool'   => true,
			'option_int'    => 12,
			'option_float'  => 12.5,
			'option_string' => 'opt',
			'option_array'  => [''],
		]);

		$this->assertEquals('php', $cmd->getProgram());
		$this->assertEquals(0, count($cmd->getArguments()));
		$this->assertEquals(6, count($cmd->getOptions()));
		$this->assertEquals([
								'option_null'   => null,
								'option_bool'   => true,
								'option_int'    => 12,
								'option_float'  => 12.5,
								'option_string' => 'opt',
								'option_array'  => [''],
							],
							$cmd->getOptions());

		if (\Ark4ne\Processes\System\OS\Manager::isWin()) {
			$this->assertEquals('php --option_null --option_bool="1" --option_int="12" --option_float="12.5" --option_string="opt" --option_array="[""""]"',
								$cmd->getCommandLine());
		}
		else {
			$this->assertEquals('php --option_null --option_bool="1" --option_int="12" --option_float="12.5" --option_string="opt" --option_array="[\\"\\"]"',
								$cmd->getCommandLine());
		}
	}

	public function testOptionsTypeSerializable()
	{
		$cmd = new Command('php', __DIR__ . '/command/cmdSerialize.php', [
			'objSerialize' => new ObjectSerializable(),
		]);

		$this->assertEquals(1, count($cmd->getOptions()));
		$this->assertEquals(['objSerialize' => new ObjectSerializable()], $cmd->getOptions());

		if (\Ark4ne\Processes\System\OS\Manager::isWin()) {
			$this->assertEquals('php ' . __DIR__ . '/command/cmdSerialize.php --objSerialize="C:18:""ObjectSerializable"":11:{s:4:""test"";}"',
								$cmd->getCommandLine());
		}
		else {
			$this->assertEquals('php ' . __DIR__ . '/command/cmdSerialize.php --objSerialize="C:18:\\"ObjectSerializable\\":11:{s:4:\\"test\\";}"',
								$cmd->getCommandLine());
		}
		$exec = $cmd->exec();
		$this->assertTrue(is_array($exec));
		$this->assertEquals('true', $exec[0]);
	}

	public function testOptionsTypeJson()
	{
		$cmd = new Command('php', __DIR__ . '/command/cmdJson.php', [
			'objJson' => [1, 2, 3],
		]);

		$this->assertEquals(1, count($cmd->getOptions()));
		$this->assertEquals(['objJson' => [1, 2, 3]], $cmd->getOptions());

		if (\Ark4ne\Processes\System\OS\Manager::isWin()) {
			$this->assertEquals('php ' . __DIR__ . '/command/cmdJson.php --objJson="[1,2,3]"',
								$cmd->getCommandLine());
		}
		else {
			$this->assertEquals('php ' . __DIR__ . '/command/cmdJson.php --objJson="[1,2,3]"',
								$cmd->getCommandLine());
		}
		$exec = $cmd->exec();
		$this->assertTrue(is_array($exec));
		$this->assertEquals('true', $exec[0]);
	}

	public function testEmptyException()
	{
		$this->setExpectedException('\Ark4ne\Processes\Exception\CommandEmptyException');

		new Command(null);
	}

	public function testExecBackground()
	{
		if (\Ark4ne\Processes\System\OS\Manager::isLinux()) {
			$command = new Command('php', __DIR__ . '/command/basic.php');
			$this->assertInstanceOf('\Ark4ne\Processes\Process\Process', $command->exec(true));
		}
		if (\Ark4ne\Processes\System\OS\Manager::isWin()) {
			$command = new Command('php', __DIR__ . '\command\basic.php');
			$this->assertNull($command->exec(true));
		}
	}
}