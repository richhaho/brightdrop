<?php
namespace App\Custom;

use App\Globals;

class Veem
{
  /**
   * @var string The Veem API params to be used for requests.
   */
  public static $clientId;
  public static $clientSecret;
  public static $baseURL;
  public static $accessToken;
  public static $businessName;
  public static $global;
  
  /**
   * Sets the clientId to be used for requests.
   *
   * @param string $clientId
   */
  public static function setClientId($clientId)
  {
    self::$clientId = $clientId;
  }
  
  /**
   * Sets the clientSecret to be used for requests.
   *
   * @param string $clientSecret
   */
  public static function setClientSecret($clientSecret)
  {
    self::$clientSecret = $clientSecret;
  }

  /**
   * Sets the baseURL to be used for requests.
   *
   * @param string $baseURL
   */
  public static function setBaseURL($baseURL)
  {
    self::$baseURL = $baseURL;
  }

  /**
   * Sets the accessToken to be used for requests.
   *
   * @param string $accessToken
   */
  public static function setAccessToken($accessToken)
  {
    self::$accessToken = $accessToken;
  }
  
  /**
   * Gets the Base URL.
   *
   * @param string $baseURL
   */
  public static function getBaseURL()
  {
    return self::$baseURL;
  }

  /**
   * Create a new Veem instance.
   *
   * @return void
   */
  public function __construct()
  {
    $BD=Globals::first();
    self::$global = $BD;
    self::$clientId = $BD->veem_client_id;
    self::$clientSecret = $BD->veem_client_secret;
    self::$baseURL = $BD->veem_api_url;
    self::$businessName = $BD->veem_business_name;
  }

  public static function getAccessToken()
  {
    $tokenURL = self::$baseURL.'/oauth/token';
    $base64EncodedCredentials = base64_encode(self::$clientId.':'.self::$clientSecret);
    $headers=array(
      "Authorization: Basic $base64EncodedCredentials",
      "Accept: application/json",
      'Content-Type: application/x-www-form-urlencoded'
    );
    
    $requestBody = "grant_type=client_credentials&scope=all";
    $authorize = curl_init();
    curl_setopt($authorize, CURLOPT_URL, $tokenURL);
    curl_setopt($authorize, CURLOPT_PORT , 443);
    curl_setopt($authorize, CURLOPT_POST, 1);
    curl_setopt($authorize, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($authorize, CURLINFO_HEADER_OUT, true);
    curl_setopt($authorize, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($authorize, CURLOPT_POSTFIELDS, $requestBody);
    curl_setopt($authorize, CURLOPT_HEADER, 1);
    $response = curl_exec($authorize);
    $header_size = curl_getinfo($authorize, CURLINFO_HEADER_SIZE);
    curl_close($authorize);
    $response_header = substr($response, 0, $header_size);
    $response_body = substr($response, $header_size);

    $response_body=json_decode($response_body);
    $accessToken=$response_body->access_token;
    self::$accessToken = $accessToken;
    return $accessToken;
  }

  public static function createPayment($payment)
  {
    if (!self::$accessToken) {
      return 'AccessToken is null. Please try to get access token first.';
    }
    $url = self::$baseURL.'/veem/v1.1/payments';
    $accessToken = self::$accessToken;
    $requrestId = md5($payment->id);
    $headers=array(
      "X-Request-Id: $requrestId",
      "Authorization: Bearer $accessToken",
      "Accept: application/json",
      'Content-Type: application/json'
    );
    $amount = $payment->amount_updated != null ? $payment->amount_updated : $payment->amount;
    $countryCode = 'US';
    if ($payment->currency_type == 'php') $countryCode = 'PH';
    if ($payment->currency_type == 'mxn') $countryCode = 'MX';
    $data = [
      "notes" => $payment->comments,
      "approveAutomatically" => true,
      "payee" => [
          "type"          => "Business",
          "firstName"     => $payment->worker()->first_name,
          "lastName"      => $payment->worker()->last_name,
          "businessName"  => self::$businessName,
          "countryCode"   => $countryCode,
          "email"         => $payment->worker()->email_veem ? $payment->worker()->email_veem : $payment->worker()->email_main,
          "phone"         => "1"
      ],
      "payeeAmount" => [
          "currency"      => strtoupper($payment->currency_type),
          "number"        => $amount
      ],
    ];
    $requestBody = json_encode($data);
    $authorize = curl_init();
    curl_setopt($authorize, CURLOPT_URL, $url);
    curl_setopt($authorize, CURLOPT_PORT , 443);
    curl_setopt($authorize, CURLOPT_POST, 1);
    curl_setopt($authorize, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($authorize, CURLINFO_HEADER_OUT, true);
    curl_setopt($authorize, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($authorize, CURLOPT_POSTFIELDS, $requestBody);
    curl_setopt($authorize, CURLOPT_HEADER, 1);
    $response = curl_exec($authorize);
    $header_size = curl_getinfo($authorize, CURLINFO_HEADER_SIZE);
    curl_close($authorize);
    $response_header = substr($response, 0, $header_size);
    $response_body = substr($response, $header_size);

    $response_body=json_decode($response_body);
    if (isset($response_body->requestId)) {
      if ($response_body->status == 'Sent' || $response_body->status == 'PendingApproval' || $response_body->status == 'Drafted') {
        return 'success';
      }
    }
    return isset($response_body->message) ? 'An error found on Veem payment process. ' . $response_body->message : 'An error found.';
  }

}
