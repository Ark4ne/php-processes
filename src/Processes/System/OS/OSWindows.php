<?php

namespace Ark4ne\Processes\System\OS;

use Ark4ne\Processes\Command\Command;
use Ark4ne\Processes\Process\Process;

/**
 * Class OSWindows
 *
 * @package Ark4ne\Processes\System\OS
 */
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
     * @return null|string|array
     */
    public function execute(Command $command, $background = false)
    {
        $cmd = $command->getCommandLine();
        if ($background) {
            $WshShell = new \COM("WScript.Shell");

            return $WshShell->Run($cmd, 7, false);
        } else {
            exec($cmd, $exec);

            return $exec;
        }
    }

    /**
     * Kill the process.
     *
     * @param Process $process
     *
     * @return string
     */
    public function kill(Process $process)
    {
        return exec("taskkill /F /PID {$process->getPid()}");
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

        if (!is_null($filter) && !empty($filter)) {
            $filter = 'where "Caption like \'' . explode(' ',
                    $filter)[0] . '.exe\' AND CommandLine like \'%' . $filter . '%\'"';
        } else {
            $filter = '';
        }

        exec('wmic process ' . $filter . ' get caption,commandline,processid /FORMAT:CSV 2>NUL', $tasks);

        return $this->formatPSResult($tasks);
    }

    /**
     * @param $tasks
     *
     * @return array
     */
    private function formatPSResult($tasks)
    {
        array_splice($tasks, 0, 1);

        $processes = [];
        $options = [
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
     * @param int $id
     *
     * @return null|Process
     * @throw ProcessNullPIDException
     */
    public function processById($id)
    {
        if ($id) {
            $tasks = [];

            $filter = 'where "Processid like \'' . $id . '\'"';

            exec('wmic process ' . $filter . ' get caption,commandline,processid /FORMAT:CSV 2>NUL', $tasks);

            return $this->formatPSResult($tasks)[0];
        }

        return null;
    }
}
