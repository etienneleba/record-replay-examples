<?php

namespace App\RecordReplay;

enum Mode
{
    case RECORD;
    case REPLAY;
    case REPLAY_OR_RECORD;
}
