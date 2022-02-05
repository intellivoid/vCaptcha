<?php

    namespace vCaptcha\Abstracts;

    abstract class FailReason
    {
        /**
         * No fail reason has been set, the captcha state is awaiting an answer or the verification was a success
         */
        const None = 'NONE';

        /**
         * The user provided an incorrect answer, a new captcha must be generated.
         */
        const IncorrectAnswer = 'INCORRECT_ANSWER';

        /**
         * The firewall blocked the user due to it being identified as tor traffic
         */
        const TorBlocked = 'TOR_BLOCKED';

        /**
         * The firewall blocked the user due to reports of malicious activities coming from the host
         */
        const MaliciousTrafficBlocked = 'MALICIOUS_TRAFFIC_BLOCKED';

        /**
         * The captcha was already used, a new captcha must be generated
         */
        const AlreadyUsed = 'ALREADY_USED';

        /**
         * The host has changed since the time the captcha was created and the captcha was validated, this could mean
         * the captcha was resolved by a third-party source. A new captcha must be generated
         */
        const HostMismatch = 'HOST_MISMATCH';

        /**
         * The captcha has expired, a new captcha must be generated
         */
        const Expired = 'EXPIRED';

        /**
         * There was an unexpected issue on the server-side
         */
        const UnexpectedError = 'UNEXPECTED_ERROR';
    }