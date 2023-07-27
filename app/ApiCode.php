<?php

namespace App;

class ApiCode {
    public const SOMETHING_WENT_WRONG = 250;
    public const AuthenticationFailed = 401;
    public const UnprocessableEntity = 422;
    public const InvalidUser = 405;
    public const TokenNotValid = 406;
    public const ResetLinkFailed = 41;
    public const LoggedInBefore = 21;
    public const CantLogin = 31;
    public const CanLogin = 32;
    public const EmailVerifiedBefore = 33;
    public const EmailVerifiedSuccessfully = 34;
    public const VerificationLinkSent = 35;
    public const DeletionLogicError = 36;
    public const BranchHasActiveAppointments = 37;
}
