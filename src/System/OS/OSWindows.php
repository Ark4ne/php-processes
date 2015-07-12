<?php

namespace Ark4ne\Process\System;

use Ark4ne\Process\Command\Command;
use Ark4ne\Process\Process;

class OSWindows implements OSInterface
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
			if ($background) {
				pclose(popen("start /B $cmd", "r"));
			}
			else {
				return exec($cmd);
			}
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
			exec("taskkill /F /PID {$process->getPid()}");
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
			$filter = 'where "CommandLine like \'' . $filter . '%\'"';
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
				$task        = explode(",", $task);
				$processes[] = new Process($task[$options['ProcessId']],
										   $task[$options['Caption']],
										   $task[$options['CommandLine']]);
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