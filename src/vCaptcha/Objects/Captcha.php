<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace vCaptcha\Objects;

    use vCaptcha\Abstracts\CaptchaStatus;
    use vCaptcha\Abstracts\CaptchaType;
    use vCaptcha\Abstracts\FailReason;

    class Captcha
    {
        /**
         * The ID of the captcha
         *
         * @var string
         */
        public $ID;

        /**
         * The Captcha Instance ID that owns this captcha
         *
         * @var string
         */
        public $CaptchaInstanceID;

        /**
         * The captcha type that was created
         *
         * @var string|CaptchaType
         */
        public $CaptchaType;

        /**
         * The value to display to the user
         *
         * @var string|null
         */
        public $Value;

        /**
         * The captcha answer that user must provide to be correct
         *
         * @var string|null
         */
        public $Answer;

        /**
         * The IPV4/IPV6 Host that initialized the captcha verification
         *
         * @var string
         */
        public $Host;

        /**
         * The current status of the captcha
         *
         * @var string|CaptchaStatus
         */
        public $CaptchaStatus;

        /**
         * The reason the captcha verification failed if the captcha status is set to VERIFICATION_FAILED
         *
         * @var string|FailReason
         */
        public $FailReason;

        /**
         * The Unix Timestamp for when this captcha was created
         *
         * @var int
         */
        public $CreatedTimestamp;

        /**
         * The Unix Timestamp for when this captcha expires
         *
         * @var int
         */
        public $ExpirationTimestamp;

        /**
         * Syncs the captcha state
         *
         * @return void
         */
        public function sync()
        {
            if(time() >= $this->ExpirationTimestamp)
            {
                $this->CaptchaStatus = CaptchaStatus::VerificationFailed;
                $this->FailReason = FailReason::Expired;
            }
        }

        /**
         * Returns an array representation of the object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                'id' => $this->ID,
                'captcha_instance_id' => $this->CaptchaInstanceID,
                'captcha_type' => $this->CaptchaType,
                'value' => $this->Value,
                'answer' => $this->Answer,
                'host' => $this->Host,
                'captcha_status' => $this->CaptchaStatus,
                'fail_reason' => $this->FailReason,
                'created_timestamp' => $this->CreatedTimestamp,
                'expiration_timestamp' => $this->ExpirationTimestamp
            ];
        }

        /**
         * Constructs object from an array representation
         *
         * @param array $data
         * @return Captcha
         */
        public static function fromArray(array $data): Captcha
        {
            $CaptchaObject = new Captcha();

            if(isset($data['id']))
                $CaptchaObject->ID = $data['id'];

            if(isset($data['captcha_instance_id']))
                $CaptchaObject->CaptchaInstanceID = $data['captcha_instance_id'];

            if(isset($data['captcha_type']))
                $CaptchaObject->CaptchaType = $data['captcha_type'];

            if(isset($data['value']))
                $CaptchaObject->Value = $data['value'];

            if(isset($data['answer']))
                $CaptchaObject->Answer = $data['answer'];

            if(isset($data['host']))
                $CaptchaObject->Host = $data['host'];

            if(isset($data['captcha_status']))
                $CaptchaObject->CaptchaStatus = $data['captcha_status'];

            if(isset($data['fail_reason']))
                $CaptchaObject->FailReason = $data['fail_reason'];

            if(isset($data['created_timestamp']))
                $CaptchaObject->CreatedTimestamp = (int)$data['created_timestamp'];

            if(isset($data['expiration_timestamp']))
                $CaptchaObject->ExpirationTimestamp = (int)$data['expiration_timestamp'];

            $CaptchaObject->sync();
            return $CaptchaObject;
        }
    }