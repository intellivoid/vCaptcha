<?php

    require 'ppm';
    require 'net.intellivoid.vcaptcha';

    $vcaptcha = new \vCaptcha\vCaptcha();
    $instance = $vcaptcha->getCaptchaInstanceManager()->createInstance(\vCaptcha\Abstracts\CaptchaType::None, 'netkas', 'Example');

    var_dump($instance);