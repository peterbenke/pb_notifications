<?php

return [
    //'user_notifications' => [
    'user_PbNotificationsNotifications' => [
        // Make module a submodule of 'user'
        'parent' => 'user',
        'position' => ['after' => 'web_info'],
        'access' => 'use,group',
        'workspaces' => 'live',
        'iconIdentifier' => 'tx-pb_notifications-module',
        'path' => '/module/user/notifications',
        'labels' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang.xlf:module.notifications.title',
        'extensionName' => 'pb_notifications',
        'controllerActions' => [
            \PeterBenke\PbNotifications\Controller\NotificationController::class => [
                'list',
                'show',
                'markAsRead',
                'markAsUnread',
            ],
        ],
    ],
];
