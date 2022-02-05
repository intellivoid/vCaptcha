<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace vCaptcha\Managers;

    use msqg\QueryBuilder;
    use Symfony\Component\Uid\Uuid;
    use vCaptcha\Abstracts\CaptchaStatus;
    use vCaptcha\Abstracts\CaptchaType;
    use vCaptcha\Abstracts\FailReason;
    use vCaptcha\Classes\Security;
    use vCaptcha\Exceptions\CaptchaNotFoundException;
    use vCaptcha\Exceptions\DatabaseException;
    use vCaptcha\Exceptions\HostRequiredException;
    use vCaptcha\Objects\Captcha;
    use vCaptcha\Objects\CaptchaInstance;
    use vCaptcha\vCaptcha;

    class CaptchaManager
    {
        private $vcaptcha;

        /**
         * @param vCaptcha $vcaptcha
         */
        public function __construct(vCaptcha $vcaptcha)
        {
            $this->vcaptcha = $vcaptcha;
        }

        /**
         * Creates a new Captcha instance
         *
         * @param CaptchaInstance $captchaInstance
         * @param string|null $host
         * @return Captcha
         * @throws DatabaseException
         * @throws HostRequiredException
         * @noinspection PhpCastIsUnnecessaryInspection
         */
        public function createCaptcha(CaptchaInstance $captchaInstance, ?string $host=null): Captcha
        {
            $CaptchaObject = new Captcha();
            $CaptchaObject->ID = Uuid::v1()->toRfc4122();
            $CaptchaObject->CaptchaInstanceID = $captchaInstance->ID;
            $CaptchaObject->CaptchaType = $captchaInstance->CaptchaType;
            $CaptchaObject->Host = $host;
            $CaptchaObject->FailReason = FailReason::None;
            $CaptchaObject->CaptchaStatus = CaptchaStatus::AwaitingVerification;
            $CaptchaObject->CreatedTimestamp = time();
            $CaptchaObject->ExpirationTimestamp = time() + 130;

            switch($captchaInstance->CaptchaType)
            {
                case CaptchaType::ImageTextScramble:
                    $CaptchaObject->Value = Security::generateRandomString();
                    $CaptchaObject->Answer = $CaptchaObject->Value;
                    break;

                case CaptchaType::None:
                default:
                    $CaptchaObject->Value = null;
                    $CaptchaObject->Answer = null;
                    break;
            }

            if($CaptchaObject->CaptchaType == CaptchaType::None && $host == null)
                throw new HostRequiredException('The host must be set if the captcha type is None');

            if($captchaInstance->FirewallOptions->HostMismatchProtection = true)
                throw new HostRequiredException('The host must be set if Host Mismatch Protection is enabled');

            if($captchaInstance->FirewallOptions->DisallowAbusiveHosts)
                throw new HostRequiredException('The host must be set if Abusive Hosts Protection is enabled');

            if($captchaInstance->FirewallOptions->DisallowTor)
                throw new HostRequiredException('The host must be set if Tor traffic filter is enabled');

            $Query = QueryBuilder::insert_into('captcha', [
                'id' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaObject->ID),
                'captcha_instance_id' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaObject->CaptchaInstanceID),
                'captcha_type' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaObject->CaptchaType),
                'value' => ($CaptchaObject->Value == null ? null : $this->vcaptcha->getDatabase()->real_escape_string($CaptchaObject->Value)),
                'answer' => ($CaptchaObject->Answer == null ? null : $this->vcaptcha->getDatabase()->real_escape_string($CaptchaObject->Answer)),
                'host' => ($CaptchaObject->Host == null ? null : $this->vcaptcha->getDatabase()->real_escape_string($CaptchaObject->Host)),
                'fail_reason' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaObject->FailReason),
                'created_timestamp' => (int)$CaptchaObject->CreatedTimestamp,
                'expiration_timestamp' => (int)$CaptchaObject->ExpirationTimestamp
            ]);

            $QueryResults = $this->vcaptcha->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->vcaptcha->getDatabase()->error, $Query, $this->vcaptcha->getDatabase()->errno);

            return $CaptchaObject;
        }

        /**
         * Returns an existing captcha instance from the database
         *
         * @param string $captcha_id
         * @return Captcha
         * @throws CaptchaNotFoundException
         * @throws DatabaseException
         */
        public function getCaptcha(string $captcha_id): Captcha
        {
            $Query = QueryBuilder::select('captcha', [
                'id',
                'captcha_instance_id',
                'captcha_type',
                'value',
                'answer',
                'host',
                'fail_reason',
                'created_timestamp',
                'expiration_timestamp'
            ], 'id', $this->vcaptcha->getDatabase()->real_escape_string($captcha_id));

            $QueryResults = $this->vcaptcha->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->vcaptcha->getDatabase()->error, $Query, $this->vcaptcha->getDatabase()->errno);

            if($QueryResults->num_rows == 0)
                throw new CaptchaNotFoundException();

            return Captcha::fromArray($QueryResults->fetch_array(MYSQLI_ASSOC));
        }

        /**
         * Updates an existing captcha in the database
         *
         * @param Captcha $captcha
         * @return Captcha
         * @throws DatabaseException
         */
        public function updateCaptcha(Captcha $captcha): Captcha
        {
            $captcha->sync();

            $Query = QueryBuilder::update('captcha', [
                'fail_reason' => $this->vcaptcha->getDatabase()->real_escape_string($captcha->FailReason)
            ], 'id', $this->vcaptcha->getDatabase()->real_escape_string($captcha->ID));

            $QueryResults = $this->vcaptcha->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->vcaptcha->getDatabase()->error, $Query, $this->vcaptcha->getDatabase()->errno);

            return $captcha;
        }
    }