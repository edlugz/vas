<?php

namespace EdLugz\VAS\Requests;

use EdLugz\VAS\SMSClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SMS extends SMSClient
{
    /**
     * check merchant balance end point on SMS API.
     *
     * @var string
     */
    protected $sendEndPoint = 'sendmessages';
    protected $balanceEndPoint = 'getbalance';
    protected $subscribeEndPoint = 'subscribeuser';
    protected $sendSubscriptionEndPoint = 'SendMT';
    protected $replyEndPoint = 'ReplySMS';

    /**
     * The merchant code assigned for the application on SMS API.
     *
     * @var string
     */
    protected $email;

    /**
     * The merchant code assigned for the application on SMS API.
     *
     * @var string
     */
    protected $senderId;

    /**
     * Balance constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->email = config('vas.email');

        $this->senderId = config('vas.sender_id');
    }

    /**
     * Check sms credits balance.
     *
     * @param string email
     */
    public function balance()
    {
        $parameters = [
            'email' => $this->email,
        ];

        Log::info($parameters);

        $response = $this->call($this->balanceEndPoint, ['json' => $parameters], 'GET');

        Log::info(json_encode($response));

        return $response;
    }

    /**
     * Subscribe User to service.
     *
     * @param string email
     * @param string telephone
     * @param string offercode
     */
    public function subscribe($requestId = null, $mobileNumber, $offerCode)
    {
        if (is_null($requestId)) {
            $requestId = (string) Str::uuid();
        }

        $parameters = [
            'email'     => $this->email,
            'telephone' => $mobileNumber,
            'offercode' => $offerCode,
        ];

        Log::info($parameters);

        $response = $this->call($this->subscribeEndPoint, ['json' => $parameters]);

        Log::info(json_encode($response));

        return $response;
    }

    /**
     * Send Subscription Messages (MT).
     *
     * @param string email
     * @param string sender
     * @param array messages
     * telephone :	(string) the message recipient beggining with 254...
     * text : message body
     * offercode :	sending code - service in which user is subscribed
     * @param datetime schedule
     */
    public function subscriptionMessages($messages)
    {
        $requestId = (string) Str::uuid();

        $parameters = [
            'email'    => $this->email,
            'messages' => $messages,
        ];

        Log::info($parameters);

        $response = $this->call($this->sendSubscriptionEndPoint, ['json' => $parameters]);

        Log::info(json_encode($response));

        return $response;
    }

    /**
     * Send sms.
     *
     * @param string email
     * @param string sender
     * @param array sms
     * msidn : (string) The message recipient(s) begining with 07...
     * message : (string) Message to be sent to the recipient(s)
     * requestid : (string) Unique identifier of the message
     * @param datetime schedule
     */
    public function send($mobileNumber, $message, $requestId = null)
    {
        if (is_null($requestId)) {
            $requestId = (string) Str::uuid();
        }

        $parameters = [
            'email'    => $this->email,
            'sender'   => $this->senderId,
            'schedule' => date('Y-m-d H:i:s'),
            'sms'      => [
                [
                    'msisdn'    => $mobileNumber,
                    'message'   => $message,
                    'requestid' => $requestId,
                ],
            ],
        ];

        Log::info($parameters);

        $response = $this->call($this->sendEndPoint, ['json' => $parameters]);

        Log::info(json_encode($response));

        return $response;
    }

    /**
     * Reply MO Messages.
     *
     * @param string email
     * @param string telephone
     * @param string offercode
     * @param stgring message
     * @param stgring linkid
     */
    public function reply($mobileNumber, $linkId, $message, $offerCode)
    {
        $parameters = [
            'email'     => $this->email,
            'telephone' => $mobileNumber,
            'linkid'    => $linkId,
            'message'   => $message,
            'offercode' => $offerCode,
        ];

        Log::info($parameters);

        $response = $this->call($this->replyEndPoint, ['json' => $parameters]);

        Log::info(json_encode($response));

        return $response;
    }

    /**
     * Receive MO Messages in your Application ( via Callback ).
     *
     * @param string shortcode
     * @param string linkid
     * @param string offercode
     * @param stgring msisdn
     * @param stgring message
     * @param stgring timein
     */
    public function receive($data)
    {
        Log::info($data);

        //save to db
        $data->shortcode;
        $data->linkid;
        $data->offercode;
        $data->msisdn;
        $data->message;
        $data->timein;
    }
}
