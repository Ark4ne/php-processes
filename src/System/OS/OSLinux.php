<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/07/2015
 * Time: 19:02
 */

namespace Ark4ne\Process\System;

use Ark4ne\Process\Command\Command;
use Ark4ne\Process\Process;

class OSLinux implements OSInterface
{
	/**
	 * Execute the process.
	 *
	 * @param Command $command
	 * @param bool    $background
	 *
	 * @return null|string
	 */
	public function execute(Command $command, $background = false)
	{
		if ($cmd = $command->getCommandLine()) {
			return exec($cmd . ($background ? ' & 2>/dev/null' : ''));
		}

		return null;
	}

	/**
	 * Kill the process.
	 *
	 * @param Process $process
	 *
	 * @return mixed
	 */
	public function kill(Process $process)
	{
		if ($process->getPid()) {
			exec("kill -9 {$process->getPid()}");
		}
	}

	/**
	 * Return an Array of processes list in execution.
	 *
	 * @param null|string $filter
	 *
	 * @return array
	 */
	public function processes($filter = null)
	{
		$tasks = [];
		if ($filter) {
			$cmd = "ps xao pid,args | grep -v grep | grep '{$filter}'";
		}
		else {
			$cmd = "ps xao pid,args";
		}
		exec($cmd, $tasks);

		$processes = [];
		foreach ($tasks as $task) {
			$task    = explode(" ", $task);
			$command = '';
			for ($i = 2, $length = count($task); $i < $length; $i++) {
				$command .= $task[$i];
			}
			$processes[] = new Process($task[0], $task[1], $command);
		}

		return $tasks;
	}

	/**
	 * @param null|string $filter
	 *
	 * @return int
	 */
	public function countProcesses($filter = null)
	{
		if ($filter) {
			$cmd = "ps xao pid,args | grep -v grep | grep '{$filter}' | wc -l";
		}
		else {
			$cmd = "ps xao pid,args | wc -l";
		}

		return intval(exec($cmd));
	}
}