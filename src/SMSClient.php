<?php

namespace EdLugz\VAS;

use EdLugz\VAS\Exceptions\VASRequestException;
use EdLugz\VAS\Logging\Log;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use InvalidArgumentException;

class SMSClient
{
    /**
     * Guzzle client initialization.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * SMS APIs application api key.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * Base URL end points for the SMS APIs.
     *
     * @var string
     */
    protected string $base_url = 'https://reseller.standardmedia.co.ke/api/';

    /**
     * Make the initializations required to make calls to the SMS APIs
     * and throw the necessary exception if there are any missing required
     * configurations.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->validateConfigurations();

        $options = [
            'base_uri' => $this->base_url,
        ];

        if (config('vas.logs.enabled')) {
            $options = Log::enable($options);
        }

        $this->client = new Client($options);
        $this->apiKey = config('vas.api_key');
    }

    /**
     * Validate configurations.
     */
    protected function validateConfigurations(): void
    {
        // Validate keys
        if (empty(config('vas.api_key'))) {
            throw new InvalidArgumentException('api key has not been set.');
        }
        if (empty(config('vas.email'))) {
            throw new InvalidArgumentException('registered email has not been set');
        }
        if (empty(config('vas.sender_id'))) {
            throw new InvalidArgumentException('sender id has not been set');
        }
    }

    /**
     * Make API calls to SMS APIs.
     *
     * @param string $url
     * @param array  $options
     * @param string $method
     *
     * @throws VASRequestException
     *
     * @return mixed
     */
    protected function call(string $url, array $options = [], string $method = 'POST'): mixed
    {
        $options['headers'] = ['api_key' => $this->apiKey];

        try {
            $response = $this->client->request($method, $url, $options);

            $stream = $response->getBody();
            $stream->rewind();
            $content = $stream->getContents();

            return json_decode($content);
        } catch (ServerException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            if (isset($response->Envelope)) {
                $message = 'VAS SMS APIs: '.$response->Envelope->Body->Fault->faultstring;

                throw new VASRequestException($message, $e->getCode());
            }

            throw new VASRequestException(' VAS SMS APIs: '.$response->errorMessage, $e->getCode());
        } catch (ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());

            throw new VASRequestException('VAS SMS APIs: '.$response->errorMessage, $e->getCode());
        }
    }
}
