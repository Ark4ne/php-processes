<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/07/2015
 * Time: 19:02
 */

namespace Ark4ne\Processes\System;

use Ark4ne\Processes\Command\Command;
use Ark4ne\Processes\Exception\CommandEmptyException;
use Ark4ne\Processes\Exception\ProcessNullPIDException;
use Ark4ne\Processes\Process;

class OSLinux implements OSInterface
{
	/**
	 * Escape double Quote for cli.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public function escapeQuoteCli($string)
	{
		return str_replace('"', '\\"', $string);
	}

	/**
	 * Execute the command.
	 *
	 * @param Command $command
	 * @param bool    $background
	 *
	 * @return null|string
	 * @throws CommandEmptyException
	 */
	public function execute(Command $command, $background = false)
	{
		if ($cmd = $command->getCommandLine()) {
			return exec($cmd . ($background ? '  > /dev/null 2>&1 &' : ''));
		}

		throw new CommandEmptyException;
	}

	/**
	 * Kill the process.
	 *
	 * @param Process $process
	 *
	 * @return mixed
	 * @throws ProcessNullPIDException
	 */
	public function kill(Process $process)
	{
		if ($process && $process->getPid()) {
			return exec("kill -9 {$process->getPid()}");
		}
		throw new ProcessNullPIDException;
	}

	/**
	 * Return an Array of processes list in execution.
	 *
	 * @param null|string $filter
	 *
	 * @return Process[]
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
			$task    = explode(" ", trim($task));
			$command = '';
			for ($i = 2, $length = count($task); $i < $length; $i++) {
				$command .= $task[$i];
			}
			$processes[] = new Process($task[0], $task[1], $command);
		}

		return $processes;
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