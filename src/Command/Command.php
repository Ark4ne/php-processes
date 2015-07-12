<?php

namespace Ark4ne\Process\Command;

use Ark4ne\Process\System\System;

class Command
{

	/**
	 * @var string $program
	 */
	private $program;

	/**
	 * @var array $arguments
	 */
	private $arguments;

	/**
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
				foreach ($options as $opt) {
					$this->addOption($opt);
				}
			}
			else {
				$this->addOption($options);
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
	 * @param string $option
	 */
	public function addOption($option)
	{
		$this->options[md5($option)] = $option;
	}

	/**
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
		$arguments = trim(implode(' ', $this->arguments));

		$options = trim(implode(' --', $this->options));

		return "{$this->getProgram()} {$arguments} {$options}";
	}
}