<?php


namespace Omnipay\PayU;


use Omnipay\Common\AbstractGateway;

class LatamGateway extends AbstractGateway
{

    public function getName()
    {
        return "PayU Latam";
    }

    public function getDefaultParameters()
    {
        return [
            "apiKey"     => "",
            "merchantId" => "",
            "accountId"  => "",
            "testMode"   => true,
        ];
    }

    public function setMerchantId($value) {
         $this->setParameter('merchantId', $value);
    }
    public function setAccountId($value) {
         $this->setParameter('accountId', $value);
    }
    public function setApiKey($value) {
         $this->setParameter('apiKey', $value);
    }
    public function getMerchantId() {
         return $this->getParameter('merchantId');
    }
    public function getAccountId() {
         return $this->getParameter('accountId');
    }
    public function getApiKey() {
         return $this->getParameter('apiKey');
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayU\Message\Latam\PurchaseRequest', $parameters);

    }


}
