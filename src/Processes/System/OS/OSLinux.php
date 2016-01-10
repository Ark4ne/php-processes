<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/07/2015
 * Time: 19:02
 */

namespace Ark4ne\Processes\System\OS;

use Ark4ne\Processes\Command\Command;
use Ark4ne\Processes\Exception\CommandEmptyException;
use Ark4ne\Processes\Process\Process;

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
     * @param bool $background
     *
     * @return null|string
     * @throws CommandEmptyException
     */
    public function execute(Command $command, $background = false)
    {
        exec($command->getCommandLine() . ($background ? ' > /dev/null 2>&1 & echo $!' : ''), $exec);

        return $background ? $exec[0] : $exec;
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
        return exec("kill -9 {$process->getPid()}");
    }

    private function formatPSResult($tasks)
    {

        $processes = [];
        $psData = ['user', 'pid', 'cpu', 'mem', 'vsz', 'rss', 'tty', 'status', 'start', 'time', 'program'];
        foreach ($tasks as $task) {
            $task = preg_split("/\\s+/", trim($task));

            $processData = (object)[];
            foreach ($psData as $key => $data) {
                $processData->$data = $task[$key];
            }
            $command = '';
            for ($i = 11, $length = count($task); $i < $length; $i++) {
                $command .= ' ' . $task[$i];
            }
            $processData->command = trim($command);

            $processes[] = new Process($processData->pid,
                                       $processData->program,
                                       $processData->command,
                                       $processData->status,
                                       $processData->time);
        }

        return $processes;
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
        $tasks = [];//ps xa | grep -v grep | grep 'php '

        if ($filter) {//ps xa | grep -v grep | grep 'php ' | awk ' { print $1";", $2";",$3";", $4";", $5";",$6";", $7";", $8";" }'
            $cmd = "ps xau --no-headers | grep -v grep | grep '{$filter}'";
        } else {
            $cmd = "ps xau --no-headers ";
        }
        exec($cmd, $tasks);

        return $this->formatPSResult($tasks);
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
        } else {
            $cmd = "ps xao pid,args | wc -l";
        }

        return intval(exec($cmd));
    }

    /**
     * @param int $id
     *
     * @return null|Process
     * @throw ProcessNullPIDException
     */
    public function processById($id)
    {
        $tasks = [];

        if ($id = intval($id)) {
            $cmd = "ps xau -p $id --no-headers";

            exec($cmd, $tasks);

            return $tasks && count($tasks) ? $this->formatPSResult($tasks)[0] : null;
        }

        return null;
    }
}