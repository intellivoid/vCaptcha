<?php

    namespace vCaptcha\Classes;

    class Validate
    {
        /**
         * Validates the captcha instance name
         *
         * @param string $input
         * @return bool
         */
        public static function captchaInstanceName(string $input): bool
        {
            if(strlen($input) > 120)
            {
                return false;
            }

            if(strlen($input) < 3)
            {
                return false;
            }

            if(preg_match("/^[a-zA-Z0-9 ]*$/", $input))
            {
                return true;
            }

            return false;
        }
    }