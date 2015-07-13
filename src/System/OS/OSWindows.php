<?php

namespace Ark4ne\Processes\System;

use Ark4ne\Processes\Command\Command;
use Ark4ne\Processes\Exception\CommandEmptyException;
use Ark4ne\Processes\Exception\ProcessNullPIDException;
use Ark4ne\Processes\Process;

class OSWindows implements OSInterface
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
		return str_replace('"', '""', $string);
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
			if ($background) {
				pclose(popen("start /B $cmd", "r"));
			}
			else {
				return exec($cmd);
			}

			return null;
		}

		throw new CommandEmptyException;
	}

	/**
	 * Kill the process.
	 *
	 * @param Process $process
	 *
	 * @return string
	 * @throws ProcessNullPIDException
	 */
	public function kill(Process $process)
	{
		if ($process && $process->getPid()) {
			return exec("taskkill /F /PID {$process->getPid()}");
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
			$filter = 'where "Caption like \'' . explode(' ',
														 $filter)[0] . '.exe\' AND CommandLine like \'%' . $filter . '%\'"';
		}

		exec('wmic process ' . $filter . ' get caption,commandline,processid /FORMAT:CSV 2>NUL', $tasks);

		array_splice($tasks, 0, 1);

		$processes = [];
		$options   = [
			'Node'        => 0,
			'Caption'     => 0,
			'CommandLine' => 0,
			'ProcessId'   => 0,
		];
		foreach ($tasks as $key => $task) {
			if ($key == 0) {
				$task = explode(",", $task);
				foreach ($task as $k => $n) {
					$options[$n] = $k;
				}
				continue;
			}
			if (strlen($task) > 1) {
				$task = explode(",", $task);
				if ($task[$options['ProcessId']]) {
					$processes[] = new Process($task[$options['ProcessId']],
											   $task[$options['Caption']],
											   $task[$options['CommandLine']]);
				}
			}
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
		return count($this->processes($filter));
	}
}