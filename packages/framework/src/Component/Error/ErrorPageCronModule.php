<?php

namespace Shopsys\FrameworkBundle\Component\Error;

use Shopsys\Plugin\Cron\SimpleCronModuleInterface;
use Symfony\Bridge\Monolog\Logger;

class ErrorPageCronModule implements SimpleCronModuleInterface
{
    /**
     * @var \Shopsys\FrameworkBundle\Component\Error\ErrorPagesFacade
     */
    protected $errorPagesFacade;

    /**
     * @param \Shopsys\FrameworkBundle\Component\Error\ErrorPagesFacade $errorPagesFacade
     */
    public function __construct(ErrorPagesFacade $errorPagesFacade)
    {
        $this->errorPagesFacade = $errorPagesFacade;
    }

    /**
     * @inheritdoc
     */
    public function setLogger(Logger $logger): void
    {
    }

    public function run(): void
    {
        $this->errorPagesFacade->generateAllErrorPagesForProduction();
    }
}
