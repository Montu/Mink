<?php

namespace Behat\Mink\Exception;

use Behat\Mink\Session;

/*
 * This file is part of the Behat\Mink.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Mink's expectation exception.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class ExpectationException extends Exception
{
    private $session;

    /**
     * Initializes exception.
     *
     * @param string     $message   optional message
     * @param Session    $session   session instance
     * @param \Exception $exception expectation exception
     */
    public function __construct($message = null, Session $session, \Exception $exception = null)
    {
        $this->session = $session;

        parent::__construct($message ?: $exception->getMessage(), $session->getDriver());
    }

    /**
     * Returns exception session.
     *
     * @return Session
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * Returns exception message with additional context info.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $pageText = $this->pipeString($this->trimBody($this->getSession()->getPage()->getContent()) . "\n");
            $string   = sprintf("%s\n\n%s%s", $this->getMessage(), $this->getResponseInfo(), $pageText);
        } catch (\Exception $e) {
            return $this->getMessage();
        }

        return $string;
    }
}
