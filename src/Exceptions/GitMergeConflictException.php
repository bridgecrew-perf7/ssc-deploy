<?php

namespace Marcth\GocDeploy\Exceptions;

use Exception;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GitMergeConflictException extends Exception implements ExceptionInterface
{
    const MESSAGE = 'ERROR: A conflict occurred when attempting to merge branch "%s" into the main branch of "%s".';
    const CODE = 0;

    /**
     * Construct the exception.
     *
     * @param string|null $message The Exception message to throw.
     * @param int $code The Exception code.
     * @param ProcessFailedException|null $previous The previous throwable used for the exception chaining.
     * @link https://php.net/manual/en/exception.construct.php
     */
    public function __construct(string $message = null, $code = null, ProcessFailedException $previous = null) {
        if(!$message && $previous instanceof ProcessFailedException) {
            $command = explode(' ', $previous->getProcess()->getCommandLine());
            $branch = trim(array_pop($command), "'");
            $workingDirectory = $previous->getProcess()->getWorkingDirectory();

            $message = sprintf(self::MESSAGE, $branch, $workingDirectory);
        }

        parent::__construct(
            $message ?? sprintf(self::MESSAGE, 'N/A', 'N/A'),
            $code ?? self::CODE
        );
    }
}
