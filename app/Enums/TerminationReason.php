<?php

namespace App\Enums;

enum TerminationReason: int
{
    case TRIAL_PERIOD = 1;
    case MUTUAL_AGREEMENT = 2;
    case EMPLOYER_TERMINATION_ART_80 = 3;
    case CONTRACT_EXPIRY = 4;
    case RETIREMENT_AGE = 5;
    case PERMANENT_CLOSURE = 6;
    case ACTIVITY_TERMINATION = 7;
    case INCAPACITY_FOR_WORK = 8;
    case UNJUSTIFIED_TERMINATION = 9;
    case DEATH = 10;
    case WORK_INJURY_INCAPACITY = 11;

    public function label(): string
    {
        return __('messages.termination_case_' . $this->value);
    }

    public function noticePeriod(): string
    {
        $days = match($this) {
            self::TRIAL_PERIOD => 0,
            self::MUTUAL_AGREEMENT => 60,
            self::EMPLOYER_TERMINATION_ART_80 => 0,
            self::CONTRACT_EXPIRY => 60,
            self::RETIREMENT_AGE => 0,
            self::PERMANENT_CLOSURE => 60,
            self::ACTIVITY_TERMINATION => 0,
            self::INCAPACITY_FOR_WORK => 0,
            self::UNJUSTIFIED_TERMINATION => 0,
            self::DEATH => 0,
            self::WORK_INJURY_INCAPACITY => 0,
        };

        if ($days === 0) {
            return __('messages.notice_no');
        }

        return __('messages.notice_days', ['days' => $days]);
    }

    public function article(): string
    {
        return match($this) {
            self::TRIAL_PERIOD => '53',
            self::MUTUAL_AGREEMENT => '74',
            self::EMPLOYER_TERMINATION_ART_80 => '80',
            self::CONTRACT_EXPIRY => '74',
            self::RETIREMENT_AGE => '74',
            self::PERMANENT_CLOSURE => '74',
            self::ACTIVITY_TERMINATION => '74',
            self::INCAPACITY_FOR_WORK => '79',
            self::UNJUSTIFIED_TERMINATION => '77',
            self::DEATH => '79',
            self::WORK_INJURY_INCAPACITY => '137',
        };
    }
}
