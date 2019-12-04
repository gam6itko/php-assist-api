<?php

namespace Gam6itko\Assist\Exception;

class WebServiceException extends \Exception implements \JsonSerializable
{
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'code'    => $this->code,
            'message' => $this->message,
        ];
    }
}
