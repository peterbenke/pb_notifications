#
# Table structure for table 'tx_pbnotifications_domain_model_notification'
#
CREATE TABLE tx_pbnotifications_domain_model_notification
(

    uid              int(11) NOT NULL auto_increment,
    pid              int(11) DEFAULT '0' NOT NULL,

    date             int(11) DEFAULT '0' NOT NULL,
    type             int(11) DEFAULT '0' NOT NULL,
    title            varchar(255) DEFAULT '' NOT NULL,
    content          text                    NOT NULL,
    images           int(11) unsigned DEFAULT '0',
    be_groups        varchar(255) DEFAULT '' NOT NULL,
    marked_as_read   int(11) unsigned DEFAULT '0' NOT NULL,

    tstamp           int(11) unsigned DEFAULT '0' NOT NULL,
    crdate           int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id        int(11) unsigned DEFAULT '0' NOT NULL,
    deleted          tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden           tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime        int(11) unsigned DEFAULT '0' NOT NULL,
    endtime          int(11) unsigned DEFAULT '0' NOT NULL,

    t3ver_oid        int(11) DEFAULT '0' NOT NULL,
    t3ver_id         int(11) DEFAULT '0' NOT NULL,
    t3ver_wsid       int(11) DEFAULT '0' NOT NULL,
    t3ver_label      varchar(255) DEFAULT '' NOT NULL,
    t3ver_state      tinyint(4) DEFAULT '0' NOT NULL,
    t3ver_stage      int(11) DEFAULT '0' NOT NULL,
    t3ver_count      int(11) DEFAULT '0' NOT NULL,
    t3ver_tstamp     int(11) DEFAULT '0' NOT NULL,
    t3ver_move_id    int(11) DEFAULT '0' NOT NULL,

    sys_language_uid int(11) DEFAULT '0' NOT NULL,
    l10n_parent      int(11) DEFAULT '0' NOT NULL,
    l10n_diffsource  mediumblob,

    PRIMARY KEY (uid),
    KEY              parent (pid),
    KEY              t3ver_oid (t3ver_oid,t3ver_wsid),
    KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_pbnotifications_notification_backenduser_mm'
#
CREATE TABLE tx_pbnotifications_notification_backenduser_mm
(
    uid_local       int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign     int(11) unsigned DEFAULT '0' NOT NULL,
    sorting         int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    KEY             uid_local (uid_local),
    KEY             uid_foreign (uid_foreign)
);
