<?php

namespace App\RecordReplay;

use BadMethodCallException;

trait RecordReplayTrait
{
    private readonly mixed $target;
    private readonly RecordReplay $recordReplay;

    public function call(string $name, array $arguments)
    {
        if (method_exists($this->target, $name)) {
            switch ($this->recordReplay->getMode()) {
                case Mode::REPLAY:
                {
                    return $this->recordReplay->replay($this->target, $name, $arguments);
                }
                case Mode::RECORD:
                {
                    $result = call_user_func_array([$this->target, $name], $arguments);
                    $this->recordReplay->record($this->target, $name, $arguments, $result);
                    return $result;
                }
                case Mode::REPLAY_OR_RECORD:
                {
                    try {
                        return $this->recordReplay->replay($this->target, $name, $arguments);
                    } catch (ReplayDoesNotExistException $e) {
                        $result = call_user_func_array([$this->target, $name], $arguments);
                        $this->recordReplay->record($this->target, $name, $arguments, $result);
                        return $result;
                    }

                }
            }
        }
        throw new BadMethodCallException("Method $name does not exist on target object");
    }
}
