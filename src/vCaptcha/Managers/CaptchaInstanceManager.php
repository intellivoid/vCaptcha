<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace vCaptcha\Managers;

    use msqg\QueryBuilder;
    use Symfony\Component\Uid\Uuid;
    use vCaptcha\Classes\Security;
    use vCaptcha\Classes\Validate;
    use vCaptcha\Exceptions\CaptchaInstanceNotFoundException;
    use vCaptcha\Exceptions\DatabaseException;
    use vCaptcha\Exceptions\InvalidCaptchaNameException;
    use vCaptcha\Objects\CaptchaInstance;
    use vCaptcha\Objects\CaptchaInstance\FirewallOptions;
    use vCaptcha\Objects\SampleCaptchaInstance;
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

        /**
         * Creates a new Captcha instance
         *
         * @param string $captcha_type
         * @param string $owner_id
         * @param string $name
         * @return CaptchaInstance
         * @throws DatabaseException
         * @throws InvalidCaptchaNameException
         */
        public function createInstance(string $captcha_type, string $owner_id, string $name): CaptchaInstance
        {
            if(Validate::captchaInstanceName($name) == false)
                throw new InvalidCaptchaNameException('The given name must not  be greater than 120 characters or less than 3 and must be alphanumeric.');

            $CaptchaInstance = new CaptchaInstance();
            $CaptchaInstance->ID = Uuid::v4()->toRfc4122();
            $CaptchaInstance->Name = $name;
            $CaptchaInstance->SecretKey = Security::generateCaptchaInstanceSecret($CaptchaInstance->ID, $owner_id);
            $CaptchaInstance->CaptchaType = $captcha_type;
            $CaptchaInstance->OwnerID = $owner_id;
            $CaptchaInstance->Enabled = true;
            $CaptchaInstance->FirewallOptions = new FirewallOptions();
            $CaptchaInstance->CreatedTimestamp = time();
            $CaptchaInstance->LastUpdatedTimestamp = time();

            $Query = QueryBuilder::insert_into('instances', [
                'id' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->ID),
                'name' => $this->vcaptcha->getDatabase()->real_escape_string(urlencode($CaptchaInstance->Name)),
                'captcha_type' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->CaptchaType),
                'owner_id' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->OwnerID),
                'secret_key' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->SecretKey),
                'enabled' => (int)$CaptchaInstance->Enabled,
                'firewall_options' => $this->vcaptcha->getDatabase()->real_escape_string(ZiProto::encode($CaptchaInstance->FirewallOptions->toArray())),
                'created_timestamp' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->CreatedTimestamp),
                'last_updated_timestamp' => $this->vcaptcha->getDatabase()->real_escape_string($CaptchaInstance->LastUpdatedTimestamp)
            ]);
            $QueryResults = $this->vcaptcha->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->vcaptcha->getDatabase()->error, $Query, $this->vcaptcha->getDatabase()->errno);

            return $CaptchaInstance;
        }

        /**
         * Returns an existing instance from the database
         *
         * @param string $id
         * @return CaptchaInstance
         * @throws CaptchaInstanceNotFoundException
         * @throws DatabaseException
         */
        public function getInstance(string $id): CaptchaInstance
        {
            $Query = QueryBuilder::select('instances', [
                'id',
                'name',
                'captcha_type',
                'owner_id',
                'secret_key',
                'enabled',
                'firewall_options',
                'created_timestamp',
                'last_updated_timestamp'
            ], 'id', $this->vcaptcha->getDatabase()->real_escape_string($id), null, null, 1);

            $QueryResults = $this->vcaptcha->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->vcaptcha->getDatabase()->error, $Query, $this->vcaptcha->getDatabase()->errno);

            if($QueryResults->num_rows == 0)
                throw new CaptchaInstanceNotFoundException();

            $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
            $Row['firewall_options'] = ZiProto::decode($Row['firewall_options']);
            $Row['name'] = urldecode($Row['name']);

            return CaptchaInstance::fromArray($Row);
        }

        /**
         * Returns an array of captcha sample instance objects associated with the owner ID
         *
         * @param string $owner_id
         * @return SampleCaptchaInstance[]
         * @throws DatabaseException
         */
        public function getInstances(string $owner_id): array
        {
            $Query = QueryBuilder::select('instances', [
                'id',
                'captcha_type',
                'name'
            ], 'owner_id', $this->vcaptcha->getDatabase()->real_escape_string($owner_id));

            $QueryResults = $this->vcaptcha->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->vcaptcha->getDatabase()->error, $Query, $this->vcaptcha->getDatabase()->errno);

            if($QueryResults->num_rows == 0)
                return [];

            $ResultsArray = [];

            while($Row = $QueryResults->fetch_assoc())
            {
                $Row['name'] = urldecode($Row['name']);
                $ResultsArray[] = SampleCaptchaInstance::fromArray($Row);
            }

            return $ResultsArray;
        }

        /**
         * Updates an existing captcha instance in the database
         *
         * @param CaptchaInstance $captchaInstance
         * @return CaptchaInstance
         * @throws DatabaseException
         * @throws InvalidCaptchaNameException
         * @noinspection PhpCastIsUnnecessaryInspection
         */
        public function updateInstance(CaptchaInstance $captchaInstance): CaptchaInstance
        {
            if(Validate::captchaInstanceName($captchaInstance->Name) == false)
                throw new InvalidCaptchaNameException('The given name must not  be greater than 120 characters or less than 3 and must be alphanumeric.');

            $captchaInstance->LastUpdatedTimestamp = time();
            $firewall_options = $captchaInstance->FirewallOptions->toArray()['firewall_options'];

            $Query = QueryBuilder::update('instances', [
                'name' => $this->vcaptcha->getDatabase()->real_escape_string(urlencode($captchaInstance->Name)),
                'secret_key' => $this->vcaptcha->getDatabase()->real_escape_string($captchaInstance->SecretKey),
                'captcha_type' => $this->vcaptcha->getDatabase()->real_escape_string($captchaInstance->CaptchaType),
                'enabled' => (int)$captchaInstance->Enabled,
                'firewall_options' => $this->vcaptcha->getDatabase()->real_escape_string(ZiProto::encode($firewall_options)),
                'last_updated_timestamp' => (int)$captchaInstance->LastUpdatedTimestamp
            ]);

            $QueryResults = $this->vcaptcha->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->vcaptcha->getDatabase()->error, $Query, $this->vcaptcha->getDatabase()->errno);

            return $captchaInstance;
        }
    }