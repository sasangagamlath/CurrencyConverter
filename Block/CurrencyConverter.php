<?php
/**
 * Copyright © 2026 All rights reserved
 * @author Charaka Gamlath <sasangagamlath@gmail.com>
 */

declare(strict_types=1);

namespace Charaka\CurrencyConverter\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class CurrencyConverter extends Template
{
    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }
}
