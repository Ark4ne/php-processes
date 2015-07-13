<?php

namespace Ark4ne\Processes\Command;

use Ark4ne\Processes\System\OS;
use Ark4ne\Processes\System\System;

class Command
{

	/**
	 * Program to execute.
	 * @var string $program
	 */
	private $program;

	/**
	 * Arguments to add at the command line.
	 * @var array $arguments
	 */
	private $arguments;

	/**
	 * Options to add at the command line.
	 * @var array $options
	 */
	private $options;

	/**
	 * @param string            $program
	 * @param null|string|array $arguments
	 * @param null|string|array $options
	 */
	public function __construct($program, $arguments = null, $options = null)
	{
		$this->setProgram($program);

		$this->setArguments([]);
		if ($arguments) {
			if (is_array($arguments)) {
				foreach ($arguments as $arg) {
					$this->addArgument($arg);
				}
			}
			else {
				$this->addArgument($arguments);
			}
		}

		$this->setOptions([]);
		if ($options) {
			if (is_array($options)) {
				foreach ($options as $key => $opt) {
					$this->addOption($key, $opt);
				}
			}
			else {
				$this->addOption($options, '');
			}
		}
	}

	/**
	 * @return string
	 */
	public function getProgram()
	{
		return $this->program;
	}

	/**
	 * @param string $program
	 */
	public function setProgram($program)
	{
		$this->program = $program;
	}

	/**
	 * @return array
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * @param array $arguments
	 */
	public function setArguments(array $arguments)
	{
		$this->arguments = $arguments;
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @param array $options
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
	}

	/**
	 * @param string $argument
	 */
	public function addArgument($argument)
	{
		$this->arguments[md5($argument)] = $argument;
	}

	/**
	 * @param string $key
	 * @param string $option
	 */
	public function addOption($key, $option)
	{
		$this->options[$key] = $option;
	}

	/**
	 * Execute the command.
	 *
	 * @param bool $background
	 *
	 * @return mixed
	 */
	public function exec($background = false)
	{
		return System::execute($this, $background);
	}

	/**
	 * @return string
	 */
	public function getCommandLine()
	{
		$arguments = '';
		if (count($this->arguments)) {
			$arguments = ' ' . trim(implode(' ', $this->arguments));
		}
		$options = '';
		if (count($this->options)) {
			$opts = [];
			foreach ($this->options as $key => $opt) {
				switch (true) {
					case is_null($opt):
						$opts[] = $key;
						break;
					case is_bool($opt):
						$opts[] = $key . '=' . ($opt ? '1' : '0');
						break;
					case is_numeric($opt):
						$opts[] = $key . '=' . $opt;
						break;
					case is_string($opt):
						$opts[] = $key . '="' . OS::os()->escapeQuoteCli($opt) . '"';
						break;
					case $opt instanceof \Serializable;
						$opts[] = $key . '="' . OS::os()->escapeQuoteCli(serialize($opt)) . '"';
						break;
					default:
						$opts[] = $key . '="' . OS::os()->escapeQuoteCli(json_encode($opt)) . '"';
						break;
				}
			}
			$options = ' ' . trim('--' . implode(' --', $opts));
		}

		return $this->getProgram() ? trim("{$this->getProgram()}{$arguments}{$options}") : null;
	}
}