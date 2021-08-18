<?php namespace TravelOS\API\Suppliers\Amara\Clients;

use Exception;
use TravelOS\API\Suppliers\Amara\Clients\Soap\MakeReservation;
use TravelOS\API\Suppliers\Amara\Clients\Soap\MakeReservationResult;
use TravelOS\API\Suppliers\Amara\Clients\Soap\ReservationInfo;
use TravelOS\API\Suppliers\Amara\Clients\Soap\ReservationRequestInfo;
use TravelOS\API\Suppliers\Amara\Clients\Soap\ValidateBeforeReservation;
use TravelOS\API\Suppliers\Amara\Clients\Soap\ValidateBeforeReservationResult;

class Book extends Base
{
    public function __construct()
    {
        $WSDL_URL = static::$SANDBOX
            ? 'http://www.amaratour.ro/WebAPITest/Reservations.svc?singleWsdl'
            : 'http://www.amaratour.ro/WebAPI/Reservations.svc?singleWsdl';

        $classmap = [
            'ValidateBeforeReservationResult' => ValidateBeforeReservationResult::class,
            'MakeReservationResult'           => MakeReservationResult::class,
            'ReservationInfo'                 => ReservationInfo::class,
        ];

        parent::__construct($WSDL_URL, $classmap);
    }

    /**
     * @param ReservationRequestInfo $amaraReservation
     *
     * @throws Exception on failed booking
     */
    public function verify( ReservationRequestInfo $amaraReservation )
    {
        $this->setAction('http://tempuri.org/IReservations/ValidateBeforeReservation');

        $param              = new ValidateBeforeReservation();
        $param->userToken   = $this->token;
        $param->requestInfo = $amaraReservation;
        $response           = $this->call('ValidateBeforeReservation', [ $param ]);

        /** @var ValidateBeforeReservationResult $result */
        $result = $response->ValidateBeforeReservationResult;

        if($result->isValid()) return;

        throw new Exception($result->ResultMessage);
    }

    private function setAction( string $action )
    {
        $to = static::$SANDBOX
            ? 'http://www.amaratour.ro/WebAPITest/Reservations.svc'
            : 'http://www.amaratour.ro/WebAPI/Reservations.svc';

        return $this->setHeaders($to, $action);
    }

    /**
     * @param ReservationRequestInfo $amaraReservation
     *
     * @return ReservationInfo
     */
    public function book( ReservationRequestInfo $amaraReservation )
    {
        $this->setAction('http://tempuri.org/IReservations/MakeReservation');

        $param              = new MakeReservation();
        $param->userToken   = $this->token;
        $param->requestInfo = $amaraReservation;
        $response           = $this->call('MakeReservation', [ $param ]);

        /** @var MakeReservationResult $result */
        $result = $response->MakeReservationResult;

        if( ! $result->isValid()) throw new Exception($result->ResultMessage);

        return $result->ReservationInfo;
    }
}
