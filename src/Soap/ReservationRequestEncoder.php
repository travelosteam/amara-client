<?php namespace TravelOS\API\Suppliers\Amara\Clients\Soap;

use Carbon\Carbon;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class ReservationRequestEncoder implements SerializerAwareInterface, EncoderInterface
{
    use SerializerAwareTrait;

    const FORMAT = 'ReservationRequestInfo';

    /**
     * @inheritDoc
     */
    public function encode( $data, $format, array $context = [] )
    {
        /*
         * Amara primeste o lista de camere dar se verifica
         * cu un pret al uneia dintre ele drept urmare
         * poate verifica o singura camera
         */
        $reservation = new ReservationRequestInfo();

        $reservation->CachedPrice         = data_get($data, 'price.gross') / 100; // ca n-am zecimale
        $reservation->CachedPriceCurrency = data_get($data, 'price.currency');
        $reservation->Rooms               = $this->encodeRooms($data);

        return $reservation;
    }

    private function encodeRooms( $data )
    {
        $room                             = new ReservationRoom();
        $room->RoomCombinationID          = data_get($data, 'extra.RCPID');
        $room->RoomCombinationDescription = data_get($data, 'extra.RCPIDD');
        $room->Tourists                   = $this->encodeTourists($data);

        return [ $room ];
    }

    private function encodeTourists( $data )
    {
        $tourists = [];
        foreach(data_get($data, 'passengers') as $passenger) {
            $birthDate          = Carbon::make(data_get($passenger, 'birthDate'));
            $tourist            = new ReservationTourist();
            $tourist->FirstName = data_get($passenger, 'firstName');
            $tourist->LastName  = data_get($passenger, 'lastName');
            $tourist->BirthDate = $birthDate->toDateString();
            $tourist->IsMale    = 'm' == data_get($passenger, 'gender');
            $tourist->IsAdult   = 'adult' == data_get($passenger, 'type');
            $tourist->IsInfant  = 1 > $birthDate->diffInYears();

            $tourists[] = $tourist;
        }

        return $tourists;
    }

    /**
     * @inheritDoc
     */
    public function supportsEncoding( $format )
    {
        return static::FORMAT == $format;
    }
}
