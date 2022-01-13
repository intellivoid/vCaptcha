<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace vCaptcha\Managers;

    use vCaptcha\vCaptcha;

    class CaptchaInstanceManager
    {
        private $vcaptcha;

        /**
         * @param vCaptcha $vcaptcha
         */
        public function __construct(vCaptcha $vcaptcha)
        {
            $this->vcaptcha = $vcaptcha;
        }
    }