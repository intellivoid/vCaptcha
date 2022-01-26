<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace vCaptcha\Objects;

    class SampleCaptchaInstance
    {
        /**
         * The ID of the captcha instance
         *
         * @var string
         */
        public $ID;

        /**
         * The name of the captcha instance
         *
         * @var string
         */
        public $Name;

        /**
         * The type of captcha
         *
         * @var string
         */
        public $CaptchaType;

        /**
         * Indicates if the captcha instance is enabled or not
         *
         * @var bool
         */
        public $Enabled;

        /**
         * @return array
         */
        public function toArray(): array
        {
            return [
                'id' => $this->ID,
                'name' => $this->Name,
                'captcha_type' => $this->CaptchaType,
                'enabled' => $this->Enabled
            ];
        }

        /**
         * Constructs object from an array representation
         *
         * @param array $data
         * @return SampleCaptchaInstance
         */
        public static function fromArray(array $data): SampleCaptchaInstance
        {
            $SampleCaptchaInstance = new SampleCaptchaInstance();

            if(isset($data['id']))
                $SampleCaptchaInstance->ID = $data['id'];

            if(isset($data['name']))
                $SampleCaptchaInstance->Name = $data['name'];

            if(isset($data['captcha_type']))
                $SampleCaptchaInstance->CaptchaType = $data['captcha_type'];

            if(isset($data['enabled']))
                $SampleCaptchaInstance->Enabled = (bool)$data['enabled'];

            return $SampleCaptchaInstance;
        }
    }