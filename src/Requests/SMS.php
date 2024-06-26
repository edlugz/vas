<?php

namespace EdLugz\VAS\Requests;

use EdLugz\VAS\Models\VasSms;
use EdLugz\VAS\SMSClient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SMS extends SMSClient
{
    /**
     * end points on SMS API.
     *
     * @var string
     */
    protected string $sendEndPoint = 'sendmessages';
    protected string $balanceEndPoint = 'getbalance';
    protected string $subscribeEndPoint = 'subscribeuser';
    protected string $sendSubscriptionEndPoint = 'SendMT';
    protected string $replyEndPoint = 'ReplySMS';

    /**
     * The registered email assigned for the application on SMS API.
     *
     * @var string
     */
    protected string $email;

    /**
     * The sender ID assigned for the application on SMS API.
     *
     * @var string
     */
    protected string $senderId;

    /**
     * Balance constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->email = config('vas.email');

        $this->senderId = config('vas.sender_id');
    }

    /**
     * Check sms credits balance.
     * return int.
     */
    public function balance(): int
    {
        $parameters = [
            'email' => $this->email,
        ];

        $response = $this->call($this->balanceEndPoint, ['json' => $parameters], 'GET');

        if (!empty($response->MainAccountCredits[0])) {
            return $response->MainAccountCredits[0]->Balance;
        }

        return 0;
    }

    /**
     * Subscribe User to service.
     *
     * @param string $mobileNumber
     * @param string $offerCode
     * @param null   $requestId
     *
     * @throws \EdLugz\VAS\Exceptions\VASRequestException
     *
     * @return mixed
     */
    public function subscribe(string $mobileNumber, string $offerCode, $requestId = null): mixed
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
     * @param array $messages
     *                        return mixed
     *
     * @throws \EdLugz\VAS\Exceptions\VASRequestException
     */
    public function subscriptionMessages(array $messages): mixed
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
     * @param string      $mobileNumber
     * @param string      $message
     * @param array       $customFieldsKeyValue
     * @param string|null $requestId
     *
     * @throws \EdLugz\VAS\Exceptions\VASRequestException
     *
     * @return VasSms
     */
    public function send(string $mobileNumber, string $message, array $customFieldsKeyValue = [], string $requestId = null): VasSms
    {
        $requestId = (string) Str::uuid();

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

        /* @var $sms VasSms */

        $sms = VasSms::create(array_merge([
            'reference_id' => $requestId,
            'json_request' => json_encode($parameters),
        ], $customFieldsKeyValue));

        $response = $this->call($this->sendEndPoint, ['json' => $parameters]);

        $sms->update(['response' => $response]);

        return $sms;
    }

    /**
     * @param $mobileNumber
     * @param $linkId
     * @param $message
     * @param $offerCode
     *
     * @throws \EdLugz\VAS\Exceptions\VASRequestException
     *
     * @return mixed
     */
    public function reply($mobileNumber, $linkId, $message, $offerCode): mixed
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
     * @param $data
     *
     * @return void
     */
    public function receive($data): void
    {
        Log::info($data);

        //save to db
        //$data->shortcode;
        //$data->linkid;
        //$data->offercode;
        //$data->msisdn;
        //$data->message;
        //$data->timein;
    }

    /**
     * @param Request $request
     *
     * @return VasSms
     */
    public function smsReport(Request $request): VasSms
    {
        $msisdn = $cp_id = $correlator_id = $description = $delivery_status = $type = $campaign_id = null;

        if ($request->input('requestParam')) {
            $params = $request->input('requestParam')['data'];
            $keyValueParams = [];

            foreach ($params as $param) {
                $keyValueParams[$param['name']] = $param['value'];
            }

            $msisdn = $keyValueParams['Msisdn'];
            $cp_id = $keyValueParams['CpId'];
            $correlator_id = $keyValueParams['correlatorId'];
            $description = $keyValueParams['Description'];
            $delivery_status = $keyValueParams['deliveryStatus'];
            $type = $keyValueParams['Type'];
            $campaign_id = $keyValueParams['campaignId'];
        }

        $sms = VasSms::where('reference_id', substr($request->input('requestId'), 4))->first();

        if ($sms) {
            return $sms->update(
                [
                    'requestId'        => $request->input('requestId'),
                    'requestTimeStamp' => $request->input('requestTimeStamp'),
                    'channel'          => $request->input('channel'),
                    'operation'        => $request->input('operation'),
                    'traceID'          => $request->input('traceID'),
                    'msisdn'           => $msisdn,
                    'cp_id'            => $cp_id,
                    'correlator_id'    => $correlator_id,
                    'description'      => $description,
                    'delivery_status'  => $delivery_status,
                    'type'             => $type,
                    'campaign_id'      => $campaign_id,
                    'json_result'      => json_encode($request->all()),
                ]
            );
        }

        return VasSms::create(
            [
                'requestId'        => $request->input('requestId'),
                'requestTimeStamp' => $request->input('requestTimeStamp'),
                'channel'          => $request->input('channel'),
                'operation'        => $request->input('operation'),
                'traceID'          => $request->input('traceID'),
                'msisdn'           => $msisdn,
                'cp_id'            => $cp_id,
                'correlator_id'    => $correlator_id,
                'description'      => $description,
                'delivery_status'  => $delivery_status,
                'type'             => $type,
                'campaign_id'      => $campaign_id,
                'json_result'      => json_encode($request->all()),
            ]
        );
    }
}
