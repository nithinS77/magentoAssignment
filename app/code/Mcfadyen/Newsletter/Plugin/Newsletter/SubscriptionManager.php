<?php

namespace Mcfadyen\Newsletter\Plugin\Newsletter;

use Magento\Framework\App\Request\Http;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Store\Model\StoreManagerInterface;

class SubscriptionManager
{
    protected $request;
   protected $subscriberFactory;
    protected $storeManager;

    public function __construct(Http $request, SubscriberFactory $subscriberFactory, StoreManagerInterface $storeManager)
    {
        $this->request = $request;
      $this->subscriberFactory = $subscriberFactory;
        $this->storeManager = $storeManager;
    }

    public function aroundSubscribe(\Magento\Newsletter\Model\SubscriptionManager $subject, callable $proceed, $email, $storeId)
    {
        if ($this->request->isPost() && $this->request->getPost('subscriber_name')) {

            $result = $proceed($email, $storeId);

            $subscriber_name = $this->request->getPost('subscriber_name');
            $websiteId = (int)$this->storeManager->getStore($storeId)->getWebsiteId();
            $subscriber = $this->subscriberFactory->create()->loadBySubscriberEmail($email, $websiteId);

            if ($subscriber->getId()) {
                $subscriber->setSubscriberName($subscriber_name);
                $subscriber->save();
            }
        }
        return $result;
    }
}