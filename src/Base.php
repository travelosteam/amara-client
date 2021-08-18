<?php namespace TravelOS\API\Suppliers\Amara\Clients;

use Illuminate\Http\Response;
use SoapClient;
use SoapHeader;
use TravelOS\API\Suppliers\Amara\Clients\Soap\UserToken;

abstract class Base extends SoapClient
{
    /** @var UserToken */
    protected $token;

    public static $SANDBOX = true;

    public function __construct( $WSDL_URL, $classmap )
    {
        $options = [
            'soap_version' => SOAP_1_2,
            'trace'        => true,
            'exception'    => true,
            'classmap'     => $classmap,
        ];

        try {
            parent::__construct($WSDL_URL, $options);
        } catch (\Exception $e) {
            echo $e->getTraceAsString();
            die;
        }
    }

    public function __doRequest( $request, $location, $action, $version, $one_way = 0 )
    {
        $res    = parent::__doRequest($request, $location, $action, $version, $one_way);
        $resArr = explode("\n", $res);

        return ( empty($res) || ! isset($resArr[ 6 ]) ) ? $res : $resArr[ 6 ];
    }

    /**
     * @param UserToken $token
     */
    public function setToken( UserToken $token )
    {
        $this->token = $token;

        return $this;
    }

    protected function call( $name, $arguments )
    {
        $response = $this->__soapCall($name, $arguments);
        if( ! $response instanceof \stdClass) throw new \BadMethodCallException('Buba la return');
        if(data_get($response, 'DownloadOfferResult.ResultCode') == Response::HTTP_FORBIDDEN)
            throw new \Exception(data_get($response, 'DownloadOfferResult.ResultMessage'));

        return $response;
    }

    /**
     * @return array
     */
    protected function getContext(): array
    {
        $lastRequestHeaders  = implode('    ', explode("\n", $this->__getLastRequestHeaders()));
        $lastRequest         = implode('    ', explode("\n", $this->__getLastRequest()));
        $lastResponseHeaders = implode('    ', explode("\n", $this->__getLastResponseHeaders()));
        $lastResponse        = implode('    ', explode("\n", $this->__getLastResponse()));

        return compact('lastRequestHeaders', 'lastRequest', 'lastResponseHeaders', 'lastResponse');
    }

    protected function setHeaders( $To, $Action )
    {
        $NS_ADDR = 'http://www.w3.org/2005/08/addressing';
        $Action  = new SoapHeader($NS_ADDR, 'Action', $Action);
        $To      = new SoapHeader($NS_ADDR, 'To', $To);

        $this->__setSoapHeaders(compact('Action', 'To'));

        return $this;
    }
}
