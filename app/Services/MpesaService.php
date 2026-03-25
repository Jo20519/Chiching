
<?php



use Illuminate\Support\Facades\Http;

class MpesaService
{
    private $baseUrl;
    private $shortcode;
    private $passkey;
    private $consumerKey;
    private $consumerSecret;

    public function __construct()
    {
        $this->baseUrl       = config('mpesa.base_url');
        $this->shortcode     = config('mpesa.shortcode');
        $this->passkey       = config('mpesa.passkey');
        $this->consumerKey   = config('mpesa.consumer_key');
        $this->consumerSecret = config('mpesa.consumer_secret');
    }

    public function getToken()
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

        return $response->json()['access_token'];
    }

    public function stkPush($phone, $amount, $accountRef)
    {
        $token     = $this->getToken();
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", [
                'BusinessShortCode' => $this->shortcode,
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'TransactionType'   => 'CustomerPayBillOnline',
                'Amount'            => $amount,
                'PartyA'            => $phone,
                'PartyB'            => $this->shortcode,
                'PhoneNumber'       => $phone,
                'CallBackURL'       => route('mpesa.callback'),
                'AccountReference'  => $accountRef,
                'TransactionDesc'   => 'Group Contribution',
            ]);

        return $response->json();
    }
}