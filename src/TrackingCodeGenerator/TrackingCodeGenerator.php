<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator;

use \Econda\Tracking;
use Econda\Tracking\OrderProcess;
use Econda\Tracking\PageView;
use Econda\Tracking\Product;
use Econda\Tracking\ProductDetailView;
use Econda\Tracking\Registration;
use Econda\Tracking\Target;
use Econda\Tracking\TransactionProduct;
use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\SdkExtension\Email;

/**
 * @inheritdoc
 */
class TrackingCodeGenerator implements TrackingCodeGeneratorInterface
{
    /**
     * URL to the emos.js script-file.
     *
     * @var string
     */
    private $url = '';

    /**
     * @var ActivePageEntityInterface
     */
    protected $activePageEntity;

    /**
     * @var PageView
     */
    protected $pageView;

    /**
     * @param ActivePageEntityInterface $activePageEntity
     * @param string                    $urlToFile
     */
    public function __construct(ActivePageEntityInterface $activePageEntity, $urlToFile = "")
    {
        $this->activePageEntity = $activePageEntity;
        $this->url = $urlToFile;
    }

    /**
     * @inheritdoc
     */
    public function generateCode(): string
    {
        $this->pageView = $this->initializePageView();
        $this->addCurrentProductDataToPageView();
        $this->addCurrentOrderProcessToPageView();
        $this->addUserRegistrationDataToPageView();
        $this->addTransactionDataToPageView();
        $this->addSearchQueryDataToPageView();
        $this->addLoginUserDataToPageView();
        $this->addCartDataToPageView();
        $this->addContactFormDataToPageView();
        $this->addNewsletterSubscriptionDataToPageView();
        $this->addEmailToPageView();

        $jsCode = '';
        if (!empty($this->url)) {
            $jsCode .= "<script type=\"text/javascript\" " .
                "src=\"" . $this->url . "\" async=\"async\">" . " </script>";
        }
        $jsCode .= (string) $this->pageView;

        return $jsCode;
    }

    /**
     * @return PageView
     */
    protected function initializePageView()
    {
        $trackingPage = new PageView([
            'siteId' => $this->activePageEntity->getSiteid(),
            'contentLabel' => $this->activePageEntity->getContent(),
            'countryId' => $this->activePageEntity->getCountryid(),
            'pageId' => $this->activePageEntity->getPageid(),
            'langId' => $this->activePageEntity->getLangid(),
        ]);
        return $trackingPage;
    }

    private function addTransactionDataToPageView()
    {
        $billing = $this->activePageEntity->getBilling();
        if ($billing && $this->activePageEntity->getBoughtProducts()) {
            $transactionProducts = [];
            foreach ($this->activePageEntity->getBoughtProducts() as $transactionProductData) {
                $transactionProducts[] = new TransactionProduct($transactionProductData);
            }
            $billingInfo = [
                'number' => $billing[0],
                'customerId' => $billing[1],
                'location' => $billing[2],
                'value' => $billing[3],
                'products' => $transactionProducts,
            ];

            $this->pageView->add(new Tracking\Order($billingInfo));
        }
    }

    private function addCurrentProductDataToPageView()
    {
        if (!empty($this->activePageEntity->getProductData() && empty($this->activePageEntity->getProductToBasket()))) {
            $product = new Product($this->activePageEntity->getProductData());
            $this->pageView->add(new ProductDetailView($product));
        }
    }

    private function addCurrentOrderProcessToPageView()
    {
        if ($this->activePageEntity->getOrderProcess()) {
            $this->pageView->add(new OrderProcess($this->activePageEntity->getOrderProcess()));
        }
    }

    private function addUserRegistrationDataToPageView()
    {
        if ($this->activePageEntity->getRegisteredUserId()) {
            $this->pageView->add(new Registration($this->activePageEntity->getRegisteredUserId(), $this->activePageEntity->getRegisteredUserResult()));
        }
    }

    private function addSearchQueryDataToPageView()
    {
        $searchQuery = $this->activePageEntity->getSearchQuery();
        if ($this->activePageEntity->getSearchQuery()) {
            $this->pageView->add(new \Econda\Tracking\Search($searchQuery, $this->activePageEntity->getSearchNumberOfHits()));
        }
    }

    private function addLoginUserDataToPageView()
    {
        $loginUserId = $this->activePageEntity->getLoginUserId();
        if ($loginUserId) {
            $this->pageView->add(new \Econda\Tracking\Login($loginUserId, $this->activePageEntity->getLoginResult()));
        }
    }

    private function addCartDataToPageView()
    {
        $productToBasket = $this->activePageEntity->getProductToBasket();
        if (!empty($productToBasket)) {
            $this->pageView->add(new Tracking\ProductAddToCart(new TransactionProduct($productToBasket)));
        }
    }

    private function addContactFormDataToPageView()
    {
        $contactMessage = $this->activePageEntity->getContactsMessage();
        if (!empty($contactMessage)) {
            $target = new Target([
                'group' => 'Kontakt',
                'name' => $contactMessage,
                'value' => 1
            ]);
            $this->pageView->add($target);
        }
    }

    private function addNewsletterSubscriptionDataToPageView()
    {
        $newsletterMessage = $this->activePageEntity->getNewsletterMessage();
        if (!empty($newsletterMessage)) {
            $target = new Target([
                'group' => 'Newsletter',
                'name' => $newsletterMessage,
                'value' => 1
            ]);
            $this->pageView->add($target);
        }
    }

    private function addEmailToPageView()
    {
        $email = $this->activePageEntity->getEmail();
        if (!empty($email)) {
            $this->pageView->add(new Email($email));
        }
    }
}
