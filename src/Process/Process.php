<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/07/2015
 * Time: 18:27
 */

namespace Ark4ne\Process;

use Ark4ne\Process\System\System;

class Process
{

	/**
	 * @var int $pid
	 */
	private $pid;

	/**
	 * @var string $program
	 */
	private $program;

	/**
	 * @var string $command
	 */
	private $command;

	/**
	 * @return int
	 */
	public function getPid()
	{
		return $this->pid;
	}

	/**
	 * @param int $pid
	 */
	public function setPid($pid)
	{
		$this->pid = $pid;
	}

	/**
	 * @return string
	 */
	public function getProgram()
	{
		return $this->program;
	}

	/**
	 * @param string $program
	 */
	public function setProgram($program)
	{
		$this->program = $program;
	}

	/**
	 * @return string
	 */
	public function getCommand()
	{
		return $this->command;
	}

	/**
	 * @param string $command
	 */
	public function setCommand($command)
	{
		$this->command = $command;
	}

	/**
	 * @param int    $pid
	 * @param string $program
	 * @param string $command
	 */
	public function __construct($pid = 0, $program = "", $command = "")
	{
		$this->setPid($pid);
		$this->setProgram($program);
		$this->setCommand($command);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return "pid:{$this->getPid()} - program:{$this->getProgram()} - command:{$this->getCommand()}";
	}

	public function kill()
	{
		System::kill($this);
	}
}