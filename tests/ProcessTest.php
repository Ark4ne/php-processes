<?php

use Ark4ne\Processes\Command\Command;
use Ark4ne\Processes\Process;
use Ark4ne\Processes\System\OS;
use Ark4ne\Processes\System\System;

class ProcessTest extends PHPUnit_Framework_TestCase
{
	public function testBasic()
	{
		$process = new Process(1);
		$this->assertEquals(1, $process->getPid());
		$this->assertEquals('', $process->getProgram());
		$this->assertEquals('', $process->getCommand());
	}

	public function testFromCreation()
	{
		$cmd = new Command('php', __DIR__ . '/command/basic.php');

		$this->assertEquals(null, $cmd->exec(true));

		$processes = System::processes('php');

		$this->assertEquals(2, count($processes));
		$this->assertInstanceOf('\Ark4ne\Processes\Process', $processes[0]);

		$this->assertContains('program:',
							  (string)$processes[0]);
		if (OS::isWin()) {
			$this->assertContains('php.exe', $processes[0]->getProgram());
		}
		else {
			$this->assertContains('php', $processes[0]->getProgram());
		}

		$processes[1]->kill();

		sleep(3);
	}

	public function testException()
	{
		$this->setExpectedException('\Ark4ne\Processes\Exception\ProcessNullPIDException');
		new Process(0);
		$this->setExpectedException('\Ark4ne\Processes\Exception\ProcessNullPIDException');
		new Process(null);

		$process = new Process(1);
		$process->setPid(0);

		System::kill($process);

		OS::os()->kill($process);
	}

	public function testKill()
	{
		$cmd = new Command('php', __DIR__ . '/command/basic.php');

		$this->assertEquals(null, $cmd->exec(true));

		$processes = System::processes('php');

		$this->assertEquals(2, count($processes));
		$this->assertInstanceOf('\Ark4ne\Processes\Process', $processes[0]);

		$processes[1]->kill();

		$processes = System::processes('php');
		$this->assertEquals(1, count($processes));

		sleep(3);
	}
}