<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\Console;

use Brs\Stdlib\Exception;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0 2014-04-30
 */
class CmdExec
{
    protected $cmd;
    protected $execStatus;
    protected $stdoutBuffor = [];
    protected $executedTimes = 0;
    protected $limitOfExecuting = 1;
    protected $outputBuffering = true;
    protected $exceededLimitOfExecutionsError = true;

    public function __construct($cmd = null)
    {
        if ($cmd) {
            call_user_func_array([$this, 'setCmd'], func_get_args());
        }
    }

    public function setCmd($cmd)
    {
        $this->cmd = call_user_func_array('sprintf', func_get_args());
        return $this;
    }

    public function getCmd()
    {
        return $this->cmd;
    }

    public function getExecutedTimes()
    {
        return $this->executedTimes;
    }

    public function setLimitOfExecuting($limitOfExecuting)
    {
        $this->limitOfExecuting = (int) $limitOfExecuting;
        return $this;
    }

    public function getLimitOfExecuting()
    {
        return $this->limitOfExecuting;
    }

    public function wasExecuted()
    {
        return 0 < $this->getExecutedTimes();
    }

    public function getStatus()
    {
        return $this->execStatus;
    }

    public function isSuccess()
    {
        return 0 === $this->getStatus();
    }

    public function getStdoutBuffer($onlyLastExecuted = true)
    {
        if ($onlyLastExecuted) {
            if (! $this->wasExecuted()) {
                return '';
            }
            return $this->stdoutBuffor[max(array_keys($this->stdoutBuffor))];
        }
        return $this->stdoutBuffor;
    }

    public function execute()
    {
        $cmd = $this->getCmd();
        if ($this->executedTimes < $this->limitOfExecuting) {
            if (empty($cmd)) {
                throw new Exception\LogicException('no commad to execute');
            }

            if ($this->outputBuffering) {
                ob_start();
            }
            // $this->stdoutLastLine = exec($cmd, $this->stdoutArr, $this->execStatus);
            $this->stdoutLastLine = passthru(sprintf('%s 2>&1', $cmd), $this->execStatus);
            $this->executedTimes++;

            if ($this->outputBuffering) {
                $this->stdoutBuffor[] = ob_get_contents();
                ob_end_clean();
            }
        } elseif ($this->exceededLimitOfExecutionsError) {
            throw new Exception\LogicException('too many execution of command ' . $cmd);
        }
        return $this;
    }
}