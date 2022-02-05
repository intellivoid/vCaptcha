<?php

    namespace vCaptcha\Abstracts;

    abstract class CaptchaStatus
    {
        /**
         * The captcha is awaiting the verification system to pass
         */
        const AwaitingVerification = 'AWAITING_VERIFICATION';

        /**
         * The captcha verification passed successfully
         */
        const VerificationPassed = 'VERIFICATION_PASSED';

        /**
         * The captcha verification failed, see the fail reason for more details
         */
        const VerificationFailed = 'VERIFICATION_FAILED';
    }