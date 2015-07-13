<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/07/2015
 * Time: 18:50
 */

namespace Ark4ne\Process\System;

use Ark4ne\Process\Command\Command;
use Ark4ne\Process\Process;

class System
{
	/**
	 * Execute the process.
	 *
	 * @param Command $command
	 * @param bool    $background
	 *
	 * @return mixed
	 */
	static public function execute(Command $command, $background = false)
	{
		return OS::os()->execute($command, $background);
	}

	/**
	 * Kill the process.
	 *
	 * @param Process $process
	 *
	 * @return mixed
	 */
	static public function kill(Process $process)
	{
		return OS::os()->kill($process);
	}

	/**
	 * Return an Array of processes list in execution.
	 *
	 * @param null $filter
	 *
	 * @return array
	 * @throws \Ark4ne\Process\Exception\OSSystemException
	 */
	static public function processes($filter = null)
	{
		return OS::os()->processes($filter);
	}

	/**
	 * @param null|string $filter
	 *
	 * @return int
	 */
	static public function countProcesses($filter = null)
	{
		return OS::os()->countProcesses($filter);
	}
}