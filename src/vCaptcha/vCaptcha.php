<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace vCaptcha;

    use acm2\acm2;
    use acm2\Exceptions\ConfigurationNotDefinedException;
    use acm2\Objects\Schema;
    use mysqli;
    use vCaptcha\Managers\CaptchaInstanceManager;
    use vCaptcha\Managers\CaptchaManager;

    class vCaptcha
    {
        /**
         * @var acm2
         */
        private $acm;

        /**
         * @var mixed
         */
        private $DatabaseConfiguration;

        /**
         * @var mysqli|null
         */
        private $DatabaseConnection;

        /**
         * @var CaptchaInstanceManager
         */
        private $CaptchaInstanceManager;

        /**
         * @var CaptchaManager
         */
        private $CaptchaManager;

        /**
         * @throws ConfigurationNotDefinedException
         */
        public function __construct()
        {
            $this->acm = new acm2('vcaptcha');

            $DatabaseSchema = new Schema();
            $DatabaseSchema->setName('Database');
            $DatabaseSchema->setDefinition('Host', '127.0.0.1');
            $DatabaseSchema->setDefinition('Port', '3306');
            $DatabaseSchema->setDefinition('Username', 'root');
            $DatabaseSchema->setDefinition('Password', 'root');
            $DatabaseSchema->setDefinition('Name', 'vcaptcha');

            $this->acm->defineSchema($DatabaseSchema);
            $this->acm->updateConfiguration();

            $this->DatabaseConfiguration = $this->acm->getConfiguration('Database');
            $this->CaptchaInstanceManager = new CaptchaInstanceManager($this);
            $this->CaptchaManager = new CaptchaManager($this);
        }

        /**
         * @return mysqli
         */
        public function getDatabase(): mysqli
        {
            if($this->DatabaseConnection == null)
            {
                $this->connectDatabase();
            }

            return $this->DatabaseConnection;
        }

        /**
         * Closes the current database connection
         */
        public function disconnectDatabase()
        {
            $this->DatabaseConnection->close();
            $this->DatabaseConnection = null;
        }

        /**
         * Creates a new database connection
         */
        public function connectDatabase()
        {
            if($this->DatabaseConnection !== null)
            {
                $this->disconnectDatabase();
            }

            $this->DatabaseConnection = new mysqli(
                $this->DatabaseConfiguration['Host'],
                $this->DatabaseConfiguration['Username'],
                $this->DatabaseConfiguration['Password'],
                $this->DatabaseConfiguration['Name'],
                $this->DatabaseConfiguration['Port']
            );
        }

        /**
         * @return CaptchaInstanceManager
         */
        public function getCaptchaInstanceManager(): CaptchaInstanceManager
        {
            return $this->CaptchaInstanceManager;
        }

        /**
         * @return CaptchaManager
         */
        public function getCaptchaManager(): CaptchaManager
        {
            return $this->CaptchaManager;
        }
    }