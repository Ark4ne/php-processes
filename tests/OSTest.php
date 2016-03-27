<?php

use Ark4ne\Processes\Command\Command;
use Ark4ne\Processes\System\OS\Manager;
use Ark4ne\Processes\System\OS\OSLinux;
use Ark4ne\Processes\System\OS\OSWindows;
use Ark4ne\Processes\System\System;

/**
 * Class OSTest
 */
class OSTest extends \PHPUnit_Framework_TestCase
{
    public function testDetection()
    {
        switch (true) {
            case stristr(PHP_OS, 'DAR'):
                $this->assertTrue(Manager::isOsx());
                $this->assertFalse(Manager::isLinux());
                $this->assertFalse(Manager::isWin());
                break;
            case stristr(PHP_OS, 'WIN'):
                $this->assertFalse(Manager::isOsx());
                $this->assertFalse(Manager::isLinux());
                $this->assertTrue(Manager::isWin());
                break;
            case stristr(PHP_OS, 'LINUX'):
                $this->assertFalse(Manager::isOsx());
                $this->assertTrue(Manager::isLinux());
                $this->assertFalse(Manager::isWin());
                break;
            default :
                $this->assertFalse(Manager::isOsx());
                $this->assertFalse(Manager::isLinux());
                $this->assertFalse(Manager::isWin());
                break;
        }
    }

    public function testOS()
    {
        switch (true) {
            case stristr(PHP_OS, 'DAR'):
                $this->assertTrue(Manager::os() instanceof OSLinux);
                break;
            case stristr(PHP_OS, 'WIN'):
                $this->assertTrue(Manager::os() instanceof OSWindows);
                break;
            case stristr(PHP_OS, 'LINUX'):
                $this->assertTrue(Manager::os() instanceof OSLinux);
                break;
            default :
                $this->setExpectedException('Ark4ne\Process\Exception\OSSystemException');
                Manager::os();
                break;
        }
    }

    public function testProcessList()
    {
        $processes = System::processes();
        $this->assertGreaterThanOrEqual(count($processes), System::countProcesses());
        $this->assertTrue(is_array($processes));
        $this->assertGreaterThan(0, count($processes));
    }

    public function testProcessFilter()
    {
        $processes = System::processes('php ');
        $this->assertTrue(is_array($processes));

        $this->assertGreaterThanOrEqual(1, count($processes));
    }

    public function testProcess()
    {
        $cmd = new Command('php', __DIR__ . '/command/basic.php');

        $this->assertEquals('php ' . __DIR__ . '/command/basic.php', $cmd->getCommandLine());

        $exec = $cmd->exec();
        $this->assertTrue(is_array($exec));
        $this->assertEquals('basic.end', $exec[0]);

        $processes = System::processes('php');

        $this->assertTrue(is_array($processes));
        $this->assertEquals(1, count($processes));
        $this->assertEquals(count($processes), System::countProcesses('php'));
    }

    public function testProcessBackground()
    {

        $cmd = new Command('php', __DIR__ . '/command/basic.php');

        $this->assertEquals('php ' . __DIR__ . '/command/basic.php', $cmd->getCommandLine());

        $cmd->exec(true);

        $processes = System::processes('php');
        $this->assertEquals(count($processes), Manager::os()->countProcesses('php'));
        $this->assertTrue(is_array($processes));
        $this->assertEquals(2, count($processes));

        $this->assertInstanceOf('\Ark4ne\Processes\Process\Process', $processes[0]);
        $this->assertInstanceOf('\Ark4ne\Processes\Process\Process', $processes[1]);

        $processes[1]->kill();
    }
}
