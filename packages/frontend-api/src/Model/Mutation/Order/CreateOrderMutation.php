<?php

declare(strict_types=1);

namespace Shopsys\FrontendApiBundle\Model\Mutation\Order;

use Overblog\GraphQLBundle\Definition\Argument;
use Shopsys\FrameworkBundle\Model\Order\Mail\OrderMailFacade;
use Shopsys\FrameworkBundle\Model\Order\Order;
use Shopsys\FrontendApiBundle\Model\Mutation\AbstractMutation;
use Shopsys\FrontendApiBundle\Model\Order\OrderDataFactory;
use Shopsys\FrontendApiBundle\Model\Order\PlaceOrderFacade;

class CreateOrderMutation extends AbstractMutation
{
    public const VALIDATION_GROUP_DIFFERENT_DELIVERY_ADDRESS = 'differentDeliveryAddress';
    public const VALIDATION_GROUP_ON_COMPANY_BEHALF = 'onCompanyBehalf';

    /**
     * @param \Shopsys\FrontendApiBundle\Model\Order\OrderDataFactory $orderDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Order\Mail\OrderMailFacade $orderMailFacade
     */
    public function __construct(
        protected readonly OrderDataFactory $orderDataFactory,
        protected readonly OrderMailFacade $orderMailFacade,
    ) {
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Order $order
     */
    protected function sendEmail(Order $order)
    {
        $this->orderMailFacade->sendEmail($order);
    }

    /**
     * @param \Overblog\GraphQLBundle\Definition\Argument $argument
     * @return string[]
     */
    protected function computeValidationGroups(Argument $argument): array
    {
        $input = $argument['input'];
        $validationGroups = ['Default'];

        if ($input[self::VALIDATION_GROUP_ON_COMPANY_BEHALF] === true) {
            $validationGroups[] = self::VALIDATION_GROUP_ON_COMPANY_BEHALF;
        }

        if ($input[self::VALIDATION_GROUP_DIFFERENT_DELIVERY_ADDRESS] === true) {
            $validationGroups[] = self::VALIDATION_GROUP_DIFFERENT_DELIVERY_ADDRESS;
        }

        return $validationGroups;
    }
}
