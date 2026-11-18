<?php
/**
 * Copyright © 2026 All rights reserved
 * @author Charaka Gamlath <sasangagamlath@gmail.com>
 */

declare(strict_types=1);

namespace Charaka\CurrencyConverter\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{
    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        private PageFactory    $pageFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(): ResultInterface
    {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->set(__('Currency Converter'));

        return $page;
    }
}
