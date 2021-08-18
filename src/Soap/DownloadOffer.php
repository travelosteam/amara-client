<?php namespace TravelOS\API\Suppliers\Amara\Clients\Soap;

class DownloadOffer
{
    /** @var UserToken */
    public $userToken;

    public static function fromUserToken( UserToken $userToken )
    {
        $param            = new static();
        $param->userToken = $userToken;

        return $param;
    }
}
