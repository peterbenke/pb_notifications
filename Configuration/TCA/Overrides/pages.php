<?php

defined('TYPO3') or die;

// Add new entry for folder properties "Contains plugin"
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_be.xlf:tx_pbnotifications_domain_model_notifications',
    'value' => 'pb_notifications',
    'icon' => 'pb-notifications-toolbar-menu-alert',
];

// Add the icon to this folder type
// Seems to work only with icon identifier like 'apps-pagetree...' and image name like 'ext-pb_notifications-folder...'
$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-pb_notifications'] = 'apps-pagetree-folder-contains-pb_notifications';

