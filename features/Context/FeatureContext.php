<?php

namespace GameBehat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelDictionary;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    use KernelDictionary;

    /**
     * @var Client
     */
    private $lastClient;

    /**
     * @var array
     */
    private $currentData = [];

    /**
     * @var array
     */
    private $currentDataInHeader = [];

    /**
     * @var array
     */
    private $resultingData = [];

    /**
     * @var array
     */
    private $rememberedData = [];

    /**
     * @var string
     */
    private $token;


    /**
     * @BeforeScenario
     */
    public static function setupDatabase()
    {
        exec('php bin/console game:reinstall');
    }

    /**
     * @BeforeStep
     */
    public function clearStuffBeforeStep()
    {
        $doctrine = $this->getContainer()->get('doctrine');

        // looks like doctrine caches its stuff in static variables or something so we need to clear it up
        $doctrine->getManager()->clear();

        /**
         * @var TokenStorageInterface $tokenStorage
         */
        $tokenStorage = $this->getContainer()->get('security.token_storage');

        $tokenStorage->setToken(null);
    }

    /**
     * @param TableNode $data
     *
     * @Given I have the following data:
     */
    public function iHaveData(TableNode $data)
    {
        $this->currentData = $this->withRememberedData($data->getHash()[0]);
    }

    /**
     * @Given I have the following data in the header:
     *
     * @param TableNode $data
     */
    public function iHaveDataInHeader(TableNode $data)
    {
        $this->currentDataInHeader = $this->withRememberedData($data->getHash()[0]);
    }

    /**
     * @param array $data
     * @return array
     */
    private function withRememberedData(array $data) : array
    {
        return array_map(function($value){
            if (preg_match('/^\{[a-zA-Z0-9]+\}$/', $value)){
                return $this->rememberedData[ltrim(rtrim($value, '}'), '{')];
            }

            return $value;
        }, $data);
    }

    /**
     * @Then I post the data to :url
     *
     * @param string $url
     */
    public function iPostData(string $url)
    {
        $this->sendRequest('POST', $url);
    }

    /**
     * @Then I post to :url
     *
     * @param string $url
     */
    public function iPost(string $url)
    {
        $this->sendRequest('POST', $url);
    }

    /**
     * @Then I get the content from :url
     *
     * @param string $url
     */
    public function iGetContent(string $url)
    {
        $this->sendRequest('GET', $url);
    }

    /**
     * @param  TableNode $data
     *
     * @Then I see the following data:
     */
    public function iSeeData(TableNode $data)
    {
        $this->iSeeCodeWithData(200, $data);
    }

    /**
     * @Then I see the following data as well:
     *
     * @param TableNode $data
     */
    public function iSeeDataAsWell(TableNode $data)
    {
        $data = $data->getHash()[0];

        $resultingData = $this->flatten($this->resultingData);

        foreach ($data as $name => $value){

            Assert::assertArrayHasKey($name, $resultingData);

            $responseValue = $resultingData[$name];

            if (preg_match('/^\{[a-zA-Z0-9]+\}$/', $value)){
                if ($value === '{string}'){
                    Assert::assertTrue(is_string($responseValue));
                } elseif ($value === '{int}'){
                    Assert::assertTrue(is_int($responseValue));
                }
            } elseif (preg_match('/^%.+%$/', $value)){
                Assert::assertContains(trim($value, '%'), $responseValue);
            } elseif ($value === '[]') {
                Assert::assertEquals([], $responseValue);
            } else {
                Assert::assertEquals($value, $responseValue);
            }
        }
    }

    /**
     * @Then I see the :code code with the following data:
     *
     * @param string $code
     * @param TableNode $data
     */
    public function iSeeCodeWithData(string $code, TableNode $data)
    {
        Assert::assertEquals($code, $this->lastClient->getResponse()->getStatusCode());

        $json = $this->lastClient->getResponse()->getContent();

        $this->resultingData = json_decode($json, true);

        $this->iSeeDataAsWell($data);
    }

    /**
     * @Then I see no content
     */
    public function isNoContent()
    {
        Assert::assertEquals(204, $this->lastClient->getResponse()->getStatusCode());
        Assert::assertEquals('', $this->lastClient->getResponse()->getContent());
    }

    /**
     * @Then I remember the value of the :field field
     *
     * @param string $field
     */
    public function iRememberField(string $field)
    {
        $this->rememberedData[$field] = $this->resultingData[$field];
    }

    /**
     * @Given I have the player with following data:
     * @param TableNode $data
     */
    public function iHavePlayerWithData(TableNode $data)
    {
        $this->currentData = $data->getHash()[0];

        $this->sendRequest('POST', '/players');

        $this->currentData = $data->getHash()[0];

        $this->sendRequest('POST', '/players/current/login');

        $response = json_decode($this->lastClient->getResponse()->getContent(), true);

        $this->token = $response['secret'];

        Assert::assertTrue(is_string($this->token) && strlen($this->token) > 10);
    }


    /**
     * @param string $url
     * @return string
     */
    private function prepareUrl(string $url) : string
    {
        return '/api' . $url;
    }

    /**
     * @param string $method
     * @param string $url
     */
    private function sendRequest(string $method, string $url)
    {
        $this->lastClient = $this->getContainer()->get('test.client');

        $servers = [];

        if ($this->token){
            $servers['HTTP_GAME_TOKEN'] = $this->token;
        }

        foreach ($this->currentDataInHeader as $name => $value) {
            $servers['HTTP_'.strtoupper(str_replace('-', '_', $name))] = $value;
        }

        $servers['CONTENT_TYPE'] = 'application/json';

        $content = json_encode($this->currentData);

        $this->lastClient->setServerParameters($servers);

        $this->lastClient->request($method, $this->prepareUrl($url), [], [], [], $content);

        $this->currentData = [];
        $this->currentDataInHeader = [];
    }

    /**
     * @param array $flatten
     * @param string $prepend
     * @return array
     */
    private function flatten(array $flatten, string $prepend = '') : array
    {
        $results = [];

        foreach ($flatten as $key => $value) {
            if (is_array($value) && $value) {
                $results = array_merge($results, $this->flatten($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }
        return $results;
    }
}
