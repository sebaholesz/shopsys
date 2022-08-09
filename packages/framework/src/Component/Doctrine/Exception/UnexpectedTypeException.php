<?php

namespace Shopsys\FrameworkBundle\Component\Doctrine\Exception;

use Exception;

class UnexpectedTypeException extends Exception implements EntityException
{
    /**
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct(string $message = '', ?Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
