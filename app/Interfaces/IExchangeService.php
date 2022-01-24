<?php

namespace App\Interfaces;

interface IExchangeService
{
   function exchangeCurrency(string $from, string $to) : array;
}
