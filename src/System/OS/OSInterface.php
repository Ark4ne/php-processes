<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/07/2015
 * Time: 18:47
 */

namespace Ark4ne\Process\System;

use Ark4ne\Process\Command\Command;
use Ark4ne\Process\Process;

interface OSInterface
{
	/**
	 * Execute the process.
	 *
	 * @param Command $command
	 * @param bool    $background
	 *
	 * @return mixed
	 */
	public function execute(Command $command, $background = false);

	/**
	 * Kill the process.
	 *
	 * @param Process $process
	 *
	 * @return mixed
	 */
	public function kill(Process $process);

	/**
	 * Return an Array of processes list in execution.
	 *
	 * @param null|string $filter
	 *
	 * @return array
	 */
	public function processes($filter = null);

	/**
	 * @param null|string $filter
	 *
	 * @return int
	 */
	public function countProcesses($filter = null);
}