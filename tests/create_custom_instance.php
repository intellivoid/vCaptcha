<?php

    require 'ppm';
    require 'net.intellivoid.vcaptcha';

    $vcaptcha = new \vCaptcha\vCaptcha();
    $instance = $vcaptcha->getCaptchaInstanceManager()->createInstance(\vCaptcha\Abstracts\CaptchaType::None, 'netkas', 'Example');
    $instance->FirewallOptions->DisallowTor = true;
    $instance->FirewallOptions->DisallowAbusiveHosts = true;
    $instance->FirewallOptions->HostMismatchProtection = true;
    $instance = $vcaptcha->getCaptchaInstanceManager()->updateInstance($instance);

    var_dump($instance);