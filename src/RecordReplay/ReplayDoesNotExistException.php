<?php

namespace App\RecordReplay;

class ReplayDoesNotExistException extends \Exception
{

    public function __construct()
    {
        parent::__construct("Replay does not exist");
    }
}
