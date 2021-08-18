<?php namespace TravelOS\API\Suppliers\Amara\Clients\Soap;

class UserToken
{
    const HASH_ALGORITHM = 'md5';

    public $DealerCode;
    public $UserName;
    public $Password;
    public $Hash;

    public function __construct( $user, $pass, $code, $key )
    {
        $this->UserName   = $user;
        $this->Password   = $pass;
        $this->DealerCode = $code;
        $this->Hash       = $this->generateHash($key);
    }

    private function generateHash( $key )
    {
        $data = '';
        foreach([ $this->DealerCode, $this->UserName, $this->Password ] as $value)
            $data .= strlen($value) . $value;
fileputco
        return hash_hmac(static::HASH_ALGORITHM, $data, $key);
    }
}
