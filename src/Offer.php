<?php namespace TravelOS\API\Suppliers\Amara\Clients;

use TravelOS\API\Suppliers\Amara\Clients\Soap\DownloadOffer;
use TravelOS\API\Suppliers\Amara\Clients\Soap\DownloadPicture;
use TravelOS\API\Suppliers\Amara\Clients\Soap\RemoteFileInfo;

class Offer extends Base
{
    public function __construct()
    {
        $WSDL_URL = static::$SANDBOX
            ? 'http://www.amaratour.ro/WebAPITest/Offer.svc?singleWsdl'
            : 'http://www.amaratour.ro/WebAPI/Offer.svc?singleWsdl';

        $classmap = [
            'RemoteFileInfo' => RemoteFileInfo::class,
        ];

        parent::__construct($WSDL_URL, $classmap);
    }

    public function DownloadOffer(): RemoteFileInfo
    {
        $this->setAction('http://tempuri.org/IOffer/DownloadOffer');

        $param  = DownloadOffer::fromUserToken($this->token);
        $result = $this->call('DownloadOffer', [ $param ]);

        return $result->DownloadOfferResult;
    }

    /**
     * @param $SHID
     * @param $P
     *
     * @return RemoteFileInfo
     */
    public function downloadPicture( $SHID, $P )
    {
        $this->setAction('http://tempuri.org/IOffer/DownloadPicture');

        $param              = new DownloadPicture();
        $param->userToken   = $this->token;
        $param->hotelID     = $SHID;
        $param->pictureName = $P;

        return $this->call('DownloadPicture', [ $param ])->DownloadPictureResult;
    }

    private function setAction( $action )
    {
        $to = static::$SANDBOX
            ? 'http://www.amaratour.ro/WebAPITest/Offer.svc'
            : 'http://www.amaratour.ro/WebAPI/Offer.svc';

        return $this->setHeaders($to, $action);
    }
}
