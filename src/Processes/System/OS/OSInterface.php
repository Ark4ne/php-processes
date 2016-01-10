<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 12/07/2015
 * Time: 18:47
 */

namespace Ark4ne\Processes\System\OS;

use Ark4ne\Processes\Command\Command;
use Ark4ne\Processes\Exception\CommandEmptyException;
use Ark4ne\Processes\Exception\ProcessNullPIDException;
use Ark4ne\Processes\Process\Process;

interface OSInterface
{
	/**
	 * Escape double Quote for cli.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public function escapeQuoteCli($string);

	/**
	 * Execute the command.
	 *
	 * @param Command $command
	 * @param bool    $background
	 *
	 * @return mixed
	 * @throws CommandEmptyException
	 */
	public function execute(Command $command, $background = false);

	/**
	 * Kill the process.
	 *
	 * @param Process $process
	 *
	 * @return mixed
	 * @throws ProcessNullPIDException
	 */
	public function kill(Process $process);

	/**
	 * Return an Array of processes list in execution.
	 *
	 * @param null|string $filter
	 *
	 * @return Process[]
	 */
	public function processes($filter = null);

	/**
	 * @param null|string $filter
	 *
	 * @return int
	 */
	public function countProcesses($filter = null);

	/**
	 * @param int $id
	 *
	 * @return null|Process
	 * @throw ProcessNullPIDException
	 */
	public function processById($id);
}