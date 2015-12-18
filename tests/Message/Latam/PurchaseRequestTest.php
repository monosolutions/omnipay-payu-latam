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
        $reference = 'TestPayU';
        $this->request->setTransactionReference($reference);
        $amount = floatval(3);
        $this->request->setAmount($amount);
        $currency = 'USD';
        $this->request->setCurrency($currency);
        $this->request->setMerchantId("500238");
        $this->request->setAccountId("500538");
        $this->request->setSiteName("{siteName}");
        $this->request->setTestMode(true);
        $this->request->setBuyerEmail("test@test.com");
        $this->request->setApiKey("6u39nqhq8ftd0hlvnjfs66eh8c");
        $this->request->setNotifyUrl("http://www.test.com/confirmation");
        $this->request->setReturnUrl("http://www.test.com/response");

        $expected                    = array();
        $expected['merchantId']      = '500238';
        $expected['description']     = "{siteName} - Order Reference #{$reference}";
        $expected['amount']          = '3';
        $expected['tax']             = '0';
        $expected['taxReturnBase']   = '0';
        $expected['signature']       = md5("6u39nqhq8ftd0hlvnjfs66eh8c~500238~{$reference}~{$amount}~{$currency}");
        $expected['accountId']       = '500538';
        $expected["test"]            = "1";
        $expected['buyerEmail']      = 'test@test.com';
        $expected['responseUrl']     = 'http://www.test.com/response';
        $expected['confirmationUrl'] = 'http://www.test.com/confirmation';
        $expected["referenceCode"]   = $reference;

        $this->assertEquals($expected, $this->request->getData());
        $response = $this->request->send();
        $this->assertInstanceOf("Omnipay\\PayU\\Message\\Latam\\PurchaseResponse", $response);
        $this->assertTrue($response->isRedirect());
        $content = $response->getRedirectResponse()->getContent();
        $this->assertContains("action=\"https://stg.", $content);
        echo $content;
    }
}
