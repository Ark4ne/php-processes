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

/**
 * Class OSLinux
 *
 * @package Ark4ne\Processes\System\OS
 */
class OSLinux implements OSInterface
{

    /**
     * @param string     $args
     * @param array|null $opts
     * @param array|null $pipes
     *
     * @return string
     */
    private function getPsCmdLine($args, array $opts = null, array $pipes = null)
    {
        $cmd = ['ps'];
        $cmd[] = $args;
        if (!empty($opts)) {
            $cmd[] = '--' . implode(' --', $opts);
        }
        if (!empty($pipes)) {
            $cmd[] = '| ' . implode(' | ', $pipes);
        }

        return implode(' ', $cmd);
    }

    /**
     * @param array $tasks
     *
     * @return array
     */
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

    /**
     * Return an Array of processes list in execution.
     *
     * @param null|string $filter
     *
     * @return null|Process[]
     */
    public function processes($filter = null)
    {
        $tasks = [];
        $pipes = null;
        if (!empty($filter)) {
            $pipes = ['grep -v grep', "grep '{$filter}'"];
        }

        exec($this->getPsCmdLine('xau', ['no-headers'], $pipes), $tasks);

        return !empty($tasks) ? $this->formatPSResult($tasks) : null;
    }

    /**
     * @param null|string $filter
     *
     * @return int
     */
    public function countProcesses($filter = null)
    {
        $pipes = [];
        if (!empty($filter)) {
            $pipes = ['grep -v grep', "grep '{$filter}'"];
        }
        $pipes[] = 'wc -l';

        return intval(exec($this->getPsCmdLine('xao pid,args', null, $pipes)));
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
            exec($this->getPsCmdLine("xau -p $id", ['no-headers']), $tasks);

            return !empty($tasks) ? $this->formatPSResult($tasks)[0] : null;
        }

        return null;
    }
}
