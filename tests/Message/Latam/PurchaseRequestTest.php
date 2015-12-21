<?php

namespace Omnipay\PayU\Message\Latam;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /** @var  PurchaseRequest */
    private $request;

    public function setUp()
    {
        $client        = $this->getHttpClient();
        $request       = $this->getHttpRequest();
        $this->request = new PurchaseRequest($client, $request);
    }

    public function testGetData()
    {
        $reference = time();
        $this->request->setTransactionReference($reference);
        $amount = floatval(30);
        $this->request->setAmount($amount);
        $currency = 'USD';
        $this->request->setCurrency($currency);
        $merchantId = "500238";
        $this->request->setMerchantId($merchantId);
        $accountId = "509171";
        $this->request->setAccountId($accountId);
        $this->request->setSiteName(" d ");
        $this->request->setTestMode(true);
        $this->request->setBuyerEmail("test@test.com");
        $apiKey = "6u39nqhq8ftd0hlvnjfs66eh8c";
        $this->request->setApiKey($apiKey);
        $this->request->setNotifyUrl("http://www.test.com/confirmation");
        $this->request->setReturnUrl("http://www.test.com/response");

        $expected                    = array();
        $expected['merchantId']      = $merchantId;
        $expected['description']     = "{$reference}";
        $expected['amount']          = '30';
        $expected['tax']             = '0';
        $expected['taxReturnBase']   = '0';
        $expected['signature']       = md5("{$apiKey}~{$merchantId}~{$reference}~{$amount}~{$currency}");
        $expected['accountId']       = $accountId;
        $expected["test"]            = "1";
        $expected['buyerEmail']      = 'test@test.com';
        $expected['responseUrl']     = 'http://www.test.com/response';
        $expected['confirmationUrl'] = 'http://www.test.com/confirmation';
        $expected["referenceCode"]   = $reference;
        $expected["currency"] = "USD";

        $this->assertEquals($expected, $this->request->getData());
        $response = $this->request->send();
        $this->assertInstanceOf("Omnipay\\PayU\\Message\\Latam\\PurchaseResponse", $response);
        $this->assertTrue($response->isRedirect());
        $content = $response->getRedirectResponse()->getContent();
        $this->assertContains("action=\"https://stg.", $content);

    }
}
