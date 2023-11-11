<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\BaseEnum;

enum ReasonEnum: string
{
    use BaseEnum;

    case INVITE_RECEIVED = 'Invite_Received';
    case INVITE_ACCEPTED = 'Invite_Accepted';
    case INVITE_REJECTED = 'Invite_Rejected';
    case INVITE_CANCELED = 'Invite_Canceled';
    case OPPONENT_SUBMITTED = 'Opponent_Submitted';
    case OPPONENT_ABSENT = 'Opponent_Absent';
    case OPPONENT_NO_SUBMIT = 'Opponent_No_Submit';
    case IMAGE_VERIFIED = 'Image_Verified';
    case CLUB_VERIFIED = 'Club_Verified';
    case WELCOME = 'Welcome';
    case JOIN_SCORES = 'Join_Scores';
    case AUTO_CANCEL_INVITER = 'Auto_Cancel_Inviter';
    case AUTO_CANCEL_INVITED = 'Auto_Cancel_Invited';
    case PRIZE_ORDERED = 'Prize_Ordered';
}
