<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/09/2024
 */


use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Tan_Checkout',
    __DIR__);
