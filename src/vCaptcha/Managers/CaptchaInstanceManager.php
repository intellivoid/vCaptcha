<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace vCaptcha\Managers;

    use msqg\QueryBuilder;
    use Symfony\Component\Uid\Uuid;
    use vCaptcha\Objects\CaptchaInstance;
    use vCaptcha\Objects\CaptchaInstance\FirewallOptions;
    use vCaptcha\vCaptcha;
    use ZiProto\ZiProto;

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

        public function createCaptcha(string $captcha_type, string $owner_id): CaptchaInstance
        {
            $CaptchaInstance = new CaptchaInstance();
            $CaptchaInstance->ID = Uuid::v4()->toRfc4122();
            $CaptchaInstance->CaptchaType = $captcha_type;
            $CaptchaInstance->OwnerID = $owner_id;
            $CaptchaInstance->Enabled = true;
            $CaptchaInstance->FirewallOptions = new FirewallOptions();
            $CaptchaInstance->CreatedTimestamp = time();
            $CaptchaInstance->LastUpdatedTimestamp = time();

            $Query = QUeryBuilder::insert_into('instances', [
                'id' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->ID),
                'captcha_type' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->CaptchaType),
                'owner_id' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->OwnerID),
                'enabled' => (int)$CaptchaInstance->Enabled,
                'firewall_options' => $this->vcaptcha->getDatabase()->real_escape_string(ZiProto::encode($CaptchaInstance->FirewallOptions->toArray())),
                'created_timestamp' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->CreatedTimestamp),
                'last_updated_timestamp' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->LastUpdatedTimestamp)
            ]);
        }
    }