<?php

namespace App\Workflows\Partials;

class RequestStates
{
    const SUBMITTED = 'submitted';
    const USER = 'user';
    const MANAGER = 'manager';
    const UNIT_HEAD = 'head';
    const VICE = 'vice';
    const FINACIAL_OFFICER = 'fo';
    const MANAGER_APPROVED = 'manager_approved';
    const MANAGER_RETURNED = 'manager_returned';
    const MANAGER_DENIED = 'manager_denied';
    const HEAD_APPROVED = 'head_approved';
    const HEAD_RETURNED = 'head_returned';
    const HEAD_DENIED = 'head_denied';
    const VICE_APPROVED = 'vice_approved';
    const VICE_RETURNED = 'vice_returned';
    const VICE_DENIED = 'vice_denied';
    const COMPLETED = 'complete';
    const FO_APPROVED = 'fo_approved';
    const FO_RETURNED = 'fo_returned';
    const FO_DENIED = 'fo_denied';
    const FINAL_APPROVED = 'final_approved';
    const FINAL_RETURNED = 'final_returned';
    const FINAL_DENIED = 'final_denied';
}
