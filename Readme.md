# TYPO3 Extension ``pb_notifications`` 


## Description

This extension manages notifications for editors in the backend.


## Introduction

### What does it do?

This extension manages notifications for editors in the backend.

* You can create two types of notifications (information / warning).
* Select the backend user groups, which should see this notification (leave empty for all users)
* Add up to 3 images to your notification to illustrate the information
* Configure (extension configuration), if a popup will be shown after the user logs into the backend, if he has unread notifications

The editors get a list of notifications in the TYPO3 backend.
If there are new, unread notifications, they will see a coloured icon according to that in the top-bar inside the TYPO3 backend.

### Screenshots

![Screenshot 01] https://raw.githubusercontent.com/peterbenke/pb_notifications/refs/tags/12.4.0/Resources/Public/Screenshots/screenshot-01.png

![Screenshot 02] https://raw.githubusercontent.com/peterbenke/pb_notifications/refs/tags/12.4.0/Resources/Public/Screenshots/screenshot-02.png

![Screenshot 03] https://raw.githubusercontent.com/peterbenke/pb_notifications/refs/tags/12.4.0/Resources/Public/Screenshots/screenshot-03.png

![Screenshot 04] https://raw.githubusercontent.com/peterbenke/pb_notifications/refs/tags/12.4.0/Resources/Public/Screenshots/screenshot-04.png


## Administration

### Installation

Install this extension via composer

    composer req peterbenke/pb-notifications

### Configuration

1. Create a sysfolder, where the notifications will be stored
2. Go to extension configuration and input the uid of this sysfolder and the number of notifications, which should be displayed in the top bar inside the TYPO3 backend
3. In the extension configuration you have the possibility to force, that a popup will be shown, after the user logs into the backend, if he has unread notifications


## UsersManual

Create your notifications in your created sysfolder (see also chapter configuration).
Add up to 3 images to your notification to illustrate the information.

Create also backend user groups, which should see this notification (leave empty for all users).

**Do not forget to allow these users toi see the module.**

Do not forget to add up to 3 images to your notification to illustrate the information.

## KnownProblems

None at the moment.

## Change log

| Version | Changes                                                                          |
|---------|----------------------------------------------------------------------------------|
| 12.4.2  | Bugfix in headline (username)                                                    |
| 12.4.1  | Bugfix, if no notifications are stored                                           |
| 12.4.0  | Compatibility TYPO3 12                                                           |
| 11.5.1  | Compatibility with TYPO3 11                                                      |
| 10.4.0  | Cleanup code                                                                     |
| 3.0.2   | Fixes: Missing Localizations of some fields                                      |
|         | Fixes: Enable rich text for Notification Content field                           |
| 3.0.1   | Compatibility to TYPO3 10, thanks to Jörg Velletti (https://github.com/velletti) |
| 2.0.0   | Compatibility to TYPO3 9, thanks to Sybille Peters (https://github.com/sypets)   |
| 1.1.7   | Enable links also, if RTE is installed                                           |
| 1.1.6   | Update documentation                                                             |
| 1.1.5   | Enable links in notifications                                                    |
| 1.1.4   | Bugfix condition to show the popup warning                                       |
| 1.1.3   | ---                                                                              |
| 1.1.2   | Change order in notification list                                                |
| 1.1.1   | Bugfix link overview                                                             |
| 1.1.0   | Possibility to show popup after user login                                       |
| 1.0.2   | TYPO3 8.7 also in composer.json                                                  |     
| 1.0.1   | TYPO3 8.7 and documentation                                                      |     
| 1.0.0   | Init Version                                                                     |            