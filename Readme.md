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
2. Go to extension configuration ("Settings") and input the uid of this sysfolder and the number of notifications, which should be displayed in the top bar inside the TYPO3 backend
3. In the extension configuration you have the possibility to force a popup to be shown, after the user logs into the backend, if he has unread notifications


## UsersManual

Create your notifications in your created sysfolder (see also chapter configuration).
Add up to 3 images to your notification to illustrate the information.

Create also backend user groups, which should see this notification (leave empty for all users).

**Do not forget to allow these users toi see the module.**

Do not forget to add up to 3 images to your notification to illustrate the information.

## KnownProblems

None at the moment.
            