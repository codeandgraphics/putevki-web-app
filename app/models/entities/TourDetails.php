<?php

namespace Models\Entities;

class TourDetails
{
    public $flights = [];
    public $info;
    public $addPayments;
    public $contents;
    public $operatorLink;
    public $actualized = false;

    public function __construct($details = null, $actualize = null)
    {
        if ($actualize) {
            $this->operatorLink = property_exists($actualize, 'operatorlink')
                ? $actualize->operatorlink
                : false;
        }
        if ($details) {
            if (property_exists($details, 'flights')) {
                foreach ($details->flights as $item) {
                    $this->flights[] = new Flight($item);
                }
            }

            $this->info = new \stdClass();
            $this->info->flags = new Flags($details->tourinfo->flags);

            $this->addPayments = property_exists(
                $details->tourinfo,
                'addpayments'
            )
                ? $details->tourinfo->addpayments
                : false;
            $this->contents = property_exists($details->tourinfo, 'contents')
                ? $details->tourinfo->contents
                : false;

            $this->actualized = true;

            if ($details->iserror && count($this->flights) === 0) {
                $this->actualized = false;
                unset($this->flights, $this->info);
            }
        }
    }
}
