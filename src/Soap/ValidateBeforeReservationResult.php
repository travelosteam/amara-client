<?php namespace TravelOS\API\Suppliers\Amara\Clients\Soap;

class ValidateBeforeReservationResult
{
    public $ResultCode;
    public $ResultMessage;

    public function isValid()
    {
        return 0 == $this->ResultCode;
    }
}
