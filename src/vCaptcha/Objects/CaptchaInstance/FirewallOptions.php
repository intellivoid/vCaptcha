<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace vCaptcha\Objects\CaptchaInstance;

    use vCaptcha\Abstracts\CountryFilterMode;

    class FirewallOptions
    {
        /**
         * Indicates if tor connections should be blocked
         *
         * @var bool
         */
        public $DisallowTor;

        /**
         * Indicates if abusive hosts should be blocked
         *
         * @var bool
         */
        public $DisallowAbusiveHosts;

        /**
         *
         * The list of country codes
         *
         * @var string[]
         */
        public $CountryFilterList;

        /**
         * @var string|CountryFilterMode
         */
        public $CountryFilterMode;

        public function __construct()
        {
            $this->DisallowTor = false;
            $this->DisallowAbusiveHosts = true;
            $this->CountryFilterList = [];
            $this->CountryFilterMode = CountryFilterMode::Disabled;
        }

        /**
         * @return array
         */
        public function toArray(): array
        {
            return [
                'disallow_tor' => $this->DisallowTor,
                'disallow_abusive_hosts' => $this->DisallowAbusiveHosts,
                'country_filter_list' => $this->CountryFilterList,
                'country_filter_mode' => $this->CountryFilterMode
            ];
        }

        /**
         * Constructs object from an array representation of the object
         *
         * @param array $data
         * @return FirewallOptions
         */
        public static function fromArray(array $data): FirewallOptions
        {
            $FirewallOptionsObject = new FirewallOptions();

            if(isset($data['disallow_tor']))
                $FirewallOptionsObject->DisallowTor = (bool)$data['disallow_tor'];

            if(isset($data['disallow_abusive_hosts']))
                $FirewallOptionsObject->DisallowAbusiveHosts = (bool)$data['disallow_abusive_hosts'];

            if(isset($data['country_filter_list']))
                $FirewallOptionsObject->CountryFilterList = $data['country_filter_list'];

            if(isset($data['country_filter_mode']))
                $FirewallOptionsObject->CountryFilterMode = $data['country_filter_mode'];

            return $FirewallOptionsObject;
        }
    }