<?php 

namespace App\Exceptions;

use Exception;

class PurchaseSureException extends Exception
{
    protected $message;
    protected $data;
    protected $code;

    public function __construct($message, $data, $code)
    {
        $this->message = $message;
        $this->data = $data;
        $this->code = $code;

        parent::__construct($this->message, $this->code ? $this->code : 500);
    }

    public function getData()
    {
        return $this->data;
    }
}