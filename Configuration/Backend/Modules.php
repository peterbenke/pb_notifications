<?php

/**
 * PbNotifications
 */

use PeterBenke\PbNotifications\Controller\Backend\NotificationController;

return [
    'user_pb_notificationsNotifications' => [
        'parent' => 'user',
        'access' => 'user',
        'iconIdentifier' => 'pb-notifications-module',
        'labels' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang.xlf:module.notifications.title',
        'extensionName' => 'PbNotifications',
        'controllerActions' => [
            NotificationController::class => [
                'list',
                'show',
                'markAsRead',
                'markAsUnread',
            ],
        ],
    ],
];
