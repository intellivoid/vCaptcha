<?php

    namespace vCaptcha\Abstracts;

    abstract class CountryFilterMode
    {
        const Disabled = 'DISABLED';

        const Whitelist = 'WHITELIST';

        const Blacklist = 'BLACKLIST';
    }