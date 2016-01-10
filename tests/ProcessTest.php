<?php

use Ark4ne\Processes\Command\Command;
use Ark4ne\Processes\Process\Process;
use Ark4ne\Processes\System\OS\Manager;
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

		if (\Ark4ne\Processes\System\OS\Manager::isWin()) {
			$this->assertNull($cmd->exec(true));
		} else {
			$process = $cmd->exec(true);
			$this->assertInstanceOf('\Ark4ne\Processes\Process\Process', $process);
			$this->assertNotEmpty($process->getState());
			$this->assertNotEmpty($process->getTime());
		}

		$processes = System::processes('php');

		$this->assertEquals(2, count($processes));
		$this->assertInstanceOf('\Ark4ne\Processes\Process\Process', $processes[0]);

		$this->assertContains('program:',
							  (string)$processes[0]);
		if (Manager::isWin()) {
			$this->assertContains('php.exe', $processes[0]->getProgram());
			$this->assertEmpty($processes[0]->getState());
			$this->assertEmpty($processes[0]->getTime());
		}
		else {
			$this->assertContains('php', $processes[0]->getProgram());
			$this->assertNotEmpty($processes[0]->getState());
			$this->assertNotEmpty($processes[0]->getTime());
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

		Manager::os()->kill($process);
	}

	public function testKill()
	{
		$cmd = new Command('php', __DIR__ . '/command/basic.php');

		if (\Ark4ne\Processes\System\OS\Manager::isWin()) {
			$this->assertNull($cmd->exec(true));
		} else {
			$this->assertInstanceOf('\Ark4ne\Processes\Process\Process', $cmd->exec(true));
		}

		$processes = System::processes('php');

		$this->assertEquals(2, count($processes));
		$this->assertInstanceOf('\Ark4ne\Processes\Process\Process', $processes[0]);

		$processes[1]->kill();

		$processes = System::processes('php');
		$this->assertEquals(1, count($processes));

		sleep(3);
	}
}