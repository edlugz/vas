<?php

namespace EdLugz\VAS;
use EdLugz\VAS\Requests\SMS;

class VAS
{
     /**
     * Initiate an sms instance
     *
     * @return Sms
     */
    public function sms()
    {
        return new SMS();
    }
}