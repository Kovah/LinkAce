<?php
return [
    'user_management' => [
        'title' => 'User Management',

        'invitations' => 'Invitations',
        'invite' => 'Invite a User',
        'invite_help' => 'Enter the email address of a user you want to invite. The user will receive an email with a link to register a new user account. The invitation is valid for 72 hours.',
        'invite_delete_confirmation' => 'Do you really want to delete this Invitation?',
        'invite_successful' => 'The invitation was sent successfully.',
        'invite_accept' => 'Accept invitation',
        'invite_accepted_by' => 'Invitation was accepted by :user (ID :id)',
        'invite_delete_successful' => 'Invitation to :email was deleted successfully.',

        'invite_notification_title' => 'You have been invited to join LinkAce!',
        'invite_notification' => 'You have been invited to join LinkAce, a social bookmarking tool. Click the button below to set up your user account. If you did not request an invitation or do not expect one, please ignore this email or contact your administrator.',

        'invite_link_invalid' => 'The invitation is expired or the link is incorrect. Please contact your administrator.',
        'invite_token_invalid' => 'The invitation link is invalid or the invitation was deleted.',
        'invite_expired' => 'The invitation is expired or was already used. Please contact your administrator to receive a new invitation.',

        'invite_valid_until' => 'Valid until :datetime',
        'invite_valid_until_info' => 'This invitation is valid until :datetime',
    ],
];
