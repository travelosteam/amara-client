<?php namespace TravelOS\API\Suppliers\Amara\Clients\Soap;

class ReservationRequestInfo
{
    public $BookIfOnRequest = false;
    public $Rooms = [];
    public $CachedPrice;
    public $CachedPriceCurrency;
}
