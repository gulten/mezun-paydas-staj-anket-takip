<?php
 namespace App;

interface SendStrategy
{
    public function send($request);
}
