<?php

namespace Ark4ne\Processes\System;

use Ark4ne\Processes\Command\Command;
use Ark4ne\Processes\Exception\CommandEmptyException;
use Ark4ne\Processes\Exception\ProcessNullPIDException;
use Ark4ne\Processes\Process\Process;
use Ark4ne\Processes\System\OS\Manager;

/**
 * Class System
 *
 * @package Ark4ne\Processes\System
 */
class System
{
    /**
     * Execute the command.
     *
     * @param Command $command
     * @param bool    $background
     *
     * @return mixed
     * @throws CommandEmptyException
     */
    static public function execute(Command $command, $background = false)
    {
        return Manager::os()->execute($command, $background);
    }

    /**
     * Kill the process.
     *
     * @param Process $process
     *
     * @return mixed
     * @throws ProcessNullPIDException
     */
    static public function kill(Process $process)
    {
        return Manager::os()->kill($process);
    }

    /**
     * Return an Array of processes list in execution.
     *
     * @param null $filter
     *
     * @return Process[]
     * @throws \Ark4ne\Processes\Exception\OSUnknownException
     */
    static public function processes($filter = null)
    {
        return Manager::os()->processes($filter);
    }

    /**
     * @param null|string $filter
     *
     * @return int
     */
    static public function countProcesses($filter = null)
    {
        return Manager::os()->countProcesses($filter);
    }

    /**
     * Return an Array of processes list in execution.
     *
     * @param null $id
     *
     * @return Process[]
     * @throws \Ark4ne\Processes\Exception\OSUnknownException
     */
    static public function processById($id)
    {
        return Manager::os()->processById($id);
    }
}