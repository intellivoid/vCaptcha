<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace vCaptcha\Objects;

    use vCaptcha\Abstracts\CaptchaType;
    use vCaptcha\Objects\CaptchaInstance\FirewallOptions;

    class CaptchaInstance
    {
        /**
         * The ID of the captcha instance
         *
         * @var string
         */
        public $ID;

        /**
         * The ID of the owner of this captcha instance
         *
         * @var string
         */
        public $OwnerID;

        /**
         * The name of the captcha instance
         *
         * @var string
         */
        public $Name;

        /**
         * The secret key used for creating and validating
         *
         * @var string
         */
        public $SecretKey;

        /**
         * Indicates if the captcha instance is enabled or not
         *
         * @var bool
         */
        public $Enabled;

        /**
         * The captcha type that is used for this captcha
         *
         * @var string|CaptchaType
         */
        public $CaptchaType;

        /**
         * Firewall options for this captcha instance
         *
         * @var FirewallOptions
         */
        public $FirewallOptions;

        /**
         * The Unix Timestamp for when this captcha instance was last updated
         *
         * @var int
         */
        public $LastUpdatedTimestamp;

        /**
         * The Unix Timestamp for when this captcha instance was created
         *
         * @var int
         */
        public $CreatedTimestamp;

        /**
         * Returns an array representation of the object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                'id' => $this->ID,
                'owner_id' => $this->OwnerID,
                'secret_key' => $this->SecretKey,
                'enabled' => $this->Enabled,
                'captcha_type' => $this->CaptchaType,
                'firewall_options' => $this->FirewallOptions->toArray(),
                'last_updated_timestamp' => $this->LastUpdatedTimestamp,
                'created_timestamp' => $this->CreatedTimestamp
            ];
        }

        /**
         * Constructs object from an array representation
         *
         * @param array $data
         * @return CaptchaInstance
         */
        public static function fromArray(array $data): CaptchaInstance
        {
            $CaptchaInstanceObject = new CaptchaInstance();

            if(isset($data['id']))
                $CaptchaInstanceObject->ID = $data['id'];

            if(isset($data['owner_id']))
                $CaptchaInstanceObject->OwnerID = $data['owner_id'];

            if(isset($data['secret_key']))
                $CaptchaInstanceObject->SecretKey = $data['secret_key'];

            if(isset($data['enabled']))
                $CaptchaInstanceObject->Enabled = (bool)$data['enabled'];

            if(isset($data['captcha_type']))
                $CaptchaInstanceObject->CaptchaType = $data['captcha_type'];

            if(isset($data['firewall_options']))
                $CaptchaInstanceObject->FirewallOptions = FirewallOptions::fromArray($data['firewall_options']);

            if(isset($data['last_updated_timestamp']))
                $CaptchaInstanceObject->LastUpdatedTimestamp = (int)$data['last_updated_timestamp'];

            if(isset($data['created_timestamp']))
                $CaptchaInstanceObject->CreatedTimestamp = (int)$data['created_timestamp'];

            return $CaptchaInstanceObject;
        }
    }