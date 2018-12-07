<?php

namespace parsaspace\Exceptions;

class HttpException extends  \Exception
{
    public function __construct($message, $code=0) {
        parent::__construct($message, $code);
    }
    public function errorMessage(){
        return  "[{$this->code}] : {$this->message}\r\n";
    }
}


?>