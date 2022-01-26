<?php

    namespace vCaptcha\Classes;

    class Security
    {
        /**
         * Peppers a data input into irreversible hash that is iterated randomly between
         * the minimum and maximum value of the given parameters
         *
         * @param string $data
         * @param int $min
         * @param int $max
         * @return string
         */
        public static function pepper(string $data, int $min = 100, int $max = 1000): string
        {
            $n = rand($min, $max);
            $res = '';
            $data = hash('whirlpool', $data);
            for ($i=0, $l=strlen($data) ; $l ; $l--)
            {
                $i = ($i+$n-1) % $l;
                $res = $res . $data[$i];
                $data = ($i ? substr($data, 0, $i) : '') . ($i < $l-1 ? substr($data, $i+1) : '');
            }
            return($res);
        }

        /**
         * Returns a 48 long unique ID for the captcha instance
         *
         * @param string $id
         * @param string $owner_id
         * @return string
         */
        public static function generateCaptchaInstanceSecret(string $id, string $owner_id): string
        {
            return hash('crc32', $id) . hash('crc32', $owner_id) . hash('haval128,3', self::pepper($id . $owner_id . time()));
        }
    }