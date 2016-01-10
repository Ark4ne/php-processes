<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/07/2015
 * Time: 18:27
 */

namespace Ark4ne\Processes\Process;

use Ark4ne\Processes\Exception\ProcessNullPIDException;
use Ark4ne\Processes\System\System;

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
     * @var string $time
     */
    private $time;

    /**
     * @var string $state
     */
    private $state;

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
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @param $pid
	 * @param string $program
	 * @param string $command
     * @param null $state
     * @param null $time
	 *
	 * @throws ProcessNullPIDException
	 */
    public function __construct($pid, $program = "", $command = "", $state = null, $time = null)
	{
		if (!$pid) {
			throw new ProcessNullPIDException;
		}
		$this->setPid($pid);
		$this->setProgram($program);
		$this->setCommand($command);
        $this->setState($state);
        $this->setTime($time);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return "pid:{$this->getPid()} - program:{$this->getProgram()} - command:{$this->getCommand()}";
	}

	/**
	 * Kill the process.
	 * @return mixed
	 */
	public function kill()
	{
		System::kill($this);
	}
}