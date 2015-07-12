<?php

/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/07/2015
 * Time: 22:57
 */
class ProcessTest extends PHPUnit_Framework_TestCase
{
	public function testBasic()
	{
		$process = new \Ark4ne\Process\Process();
		$this->assertEquals(0, $process->getPid());
		$this->assertEquals('', $process->getProgram());
		$this->assertEquals('', $process->getCommand());
	}

	public function testFromCreation()
	{
		$cmd = new Ark4ne\Process\Command\Command('php', __DIR__ . '/command/basic.php');

		$this->assertEquals(null, $cmd->exec(true));

		$processes = \Ark4ne\Process\System\System::processes('php');

		$this->assertEquals(2, count($processes));
		$this->assertInstanceOf('\Ark4ne\Process\Process', $processes[0]);

		$this->assertContains('program:php' . (\Ark4ne\Process\System\OS::isWin() ? '.exe' : ''),
							  (string)$processes[0]);
		if (\Ark4ne\Process\System\OS::isWin()) {
			$this->assertEquals('php.exe', $processes[0]->getProgram());
		}
		else {
			$this->assertEquals('php', $processes[0]->getProgram());
		}

		$processes[1]->kill();

		sleep(3);
	}

	public function testKill()
	{
		$cmd = new Ark4ne\Process\Command\Command('php', __DIR__ . '/command/basic.php');

		$this->assertEquals(null, $cmd->exec(true));

		$processes = \Ark4ne\Process\System\System::processes('php');

		$this->assertEquals(2, count($processes));
		$this->assertInstanceOf('\Ark4ne\Process\Process', $processes[0]);

		$processes[1]->kill();

		$processes = \Ark4ne\Process\System\System::processes('php');
		$this->assertEquals(1, count($processes));

		sleep(3);
	}
}