<?php

namespace Ark4ne\Processes\Command;

use Ark4ne\Processes\Exception\CommandEmptyException;
use Ark4ne\Processes\Process\Process;
use Ark4ne\Processes\System\OS\Manager;
use Ark4ne\Processes\System\System;
use Ark4ne\Support\Arr;
use Ark4ne\Support\Str;

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
     * @param $program
     * @param null $arguments
     * @param null $options
     *
     * @throws CommandEmptyException
     */
    public function __construct($program, $arguments = null, $options = null)
    {
        if (empty($program))
            throw new CommandEmptyException();

        $this->setProgram($program);

        $this->setArguments([]);
        if ($arguments) {
            if (is_array($arguments)) {
                foreach ($arguments as $arg) {
                    $this->addArgument($arg);
                }
            } else {
                $this->addArgument($arguments);
            }
        }

        $this->setOptions([]);
        if ($options) {
            if (is_array($options)) {
                foreach ($options as $key => $opt) {
                    $this->addOption($key, $opt);
                }
            } else {
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
     * @return null|string|Process
     */
    public function exec($background = false)
    {
        if ($background) {
            return System::processById(System::execute($this, true));
        }

        return System::execute($this, false);
    }

    /**
     * @return string
     */
    public function getCommandLine()
    {
        $param = [];
        $param[] = $this->argsToString();
        $param[] = $this->optsToString();
        $params = trim(implode(' ', $param));

        return trim($this->getProgram() . (strlen($params) > 0 ? ' ' . $params : ''));
    }

    private function argsToString()
    {
        return Arr::toString($this->arguments, ' ');
    }

    private function optsToString()
    {
        $options = '';
        if (count($this->options)) {
            $opts = [];
            foreach ($this->options as $key => $opt) {
                if (is_null($opt)) {
                    $opts[] = $key;
                } else {
                    $opts[] = $key . '="' . Manager::os()->escapeQuoteCli(Str::fromVar($opt)) . '"';
                }
            }
            $options = trim('--' . implode(' --', $opts));
        }
        return $options;
    }
}