<?php

use Ark4ne\Process\System\OS;

class OSTest extends \PHPUnit_Framework_TestCase
{
	public function testDetection()
	{
		switch (true) {
			case stristr(PHP_OS, 'DAR'):
				$this->assertTrue(OS::isOsx());
				$this->assertFalse(OS::isLinux());
				$this->assertFalse(OS::isWin());
				break;
			case stristr(PHP_OS, 'WIN'):
				$this->assertFalse(OS::isOsx());
				$this->assertFalse(OS::isLinux());
				$this->assertTrue(OS::isWin());
				break;
			case stristr(PHP_OS, 'LINUX'):
				$this->assertFalse(OS::isOsx());
				$this->assertTrue(OS::isLinux());
				$this->assertFalse(OS::isWin());
				break;
			default :
				$this->assertFalse(OS::isOsx());
				$this->assertFalse(OS::isLinux());
				$this->assertFalse(OS::isWin());
				break;
		}
	}

	public function testOS()
	{
		switch (true) {
			case stristr(PHP_OS, 'DAR'):
				$this->assertTrue(OS::os() instanceof \Ark4ne\Process\System\OSLinux);
				break;
			case stristr(PHP_OS, 'WIN'):
				$this->assertTrue(OS::os() instanceof \Ark4ne\Process\System\OSWindows);
				break;
			case stristr(PHP_OS, 'LINUX'):
				$this->assertTrue(OS::os() instanceof \Ark4ne\Process\System\OSLinux);
				break;
			default :
				$this->setExpectedException('Ark4ne\Process\Exception\OSSystemException');
				OS::os();
				break;
		}
	}

	public function testProcessList()
	{
		$processes = OS::os()->processes();
		$this->assertTrue(is_array($processes));
		$this->assertGreaterThan(0, count($processes));
	}

	public function testProcessFilter()
	{
		$processes = OS::os()->processes('php');
		$this->assertTrue(is_array($processes));
		$this->assertEquals(1, count($processes));
	}

	public function testProcess()
	{
		$cmd = new Ark4ne\Process\Command\Command('php', __DIR__ . '/command/basic.php');

		$this->assertEquals('php ' . __DIR__ . '/command/basic.php ', $cmd->getCommandLine());
		$this->assertEquals('basic.end', $cmd->exec());

		$processes = OS::os()->processes('php');

		$this->assertTrue(is_array($processes));
		$this->assertEquals(1, count($processes));
		$this->assertEquals(count($processes), OS::os()->countProcesses('php'));

	}

	public function testProcessBackground()
	{

		$cmd = new Ark4ne\Process\Command\Command('php', __DIR__ . '/command/basic.php');

		$cmd->exec(true);

		$processes = OS::os()->processes('php');
		$this->assertEquals(count($processes), OS::os()->countProcesses('php'));
		$this->assertTrue(is_array($processes));
		$this->assertEquals(2, count($processes));

		$processes[1]->kill();

		sleep(3);
	}
}
