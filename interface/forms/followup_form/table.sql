CREATE TABLE IF NOT EXISTS `form_followup` (
    /* both extended and encounter forms need a last modified date */
    date datetime default NULL comment 'last modified date',
    /* these fields are common to all encounter forms. */
    id bigint(20) NOT NULL auto_increment,
    pid bigint(20) NOT NULL default 0,
    user varchar(255) default NULL,
    groupname varchar(255) default NULL,
    authorized tinyint(4) default NULL,
    activity tinyint(4) default NULL,
    last_enc datetime default NULL,
    date_visit datetime default NULL,
    enc_number varchar(255),
    reason_follow varchar(255),
    diag_last varchar(255),
    follow_note TEXT,
    present_state varchar(255),
    diag_change varchar(255),
    new_diag varchar(255),
    trt_change varchar(255),
    new_inv varchar(255),
    next_visit varchar(255),
    app_done varchar(255),
    app_date datetime default NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='reas_follow',
    title='Follow up Reasons';
INSERT IGNORE INTO list_options SET list_id='reas_follow',
    option_id='1',
    title='Routine Follow up',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='reas_follow',
    option_id='2',
    title='Showing Documents and Reports',
    seq='2';

