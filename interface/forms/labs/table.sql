CREATE TABLE IF NOT EXISTS `form_labs` (
    /* both extended and encounter forms need a last modified date */
    date datetime default NULL comment 'last modified date',
    /* these fields are common to all encounter forms. */
    id bigint(20) NOT NULL auto_increment,
    pid bigint(20) NOT NULL default 0,
    user varchar(255) default NULL,
    groupname varchar(255) default NULL,
    authorized tinyint(4) default NULL,
    activity tinyint(4) default NULL,
    blood_one varchar(255),
    blood_two varchar(255),
    blood_three varchar(255),
    blood_four varchar(255),
    blood_five varchar(255),
    radio_one varchar(255),
    radio_two varchar(255),
    radio_three varchar(255),
    radio_four varchar(255),
    radio_five varchar(255),
    date_report varchar(255),
    report_upload varchar(255),
    PRIMARY KEY (id)
) ENGINE=InnoDB;
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='Blood_Investigations',
    title='Blood Investigations';
INSERT IGNORE INTO list_options SET list_id='Blood_Investigations',
    option_id='1',
    title='Hemoglobin',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='Blood_Investigations',
    option_id='2',
    title='Hb, TC, DC',
    seq='2';
INSERT IGNORE INTO list_options SET list_id='Blood_Investigations',
    option_id='3',
    title='ESR',
    seq='3';
INSERT IGNORE INTO list_options SET list_id='Blood_Investigations',
    option_id='4',
    title='Platelet Count',
    seq='4';
INSERT IGNORE INTO list_options SET list_id='Blood_Investigations',
    option_id='5',
    title='Glucose Fasting',
    seq='5';
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='Radiology_Investigations',
    title='Radiology Investigations';
INSERT IGNORE INTO list_options SET list_id='Radiology_Investigations',
    option_id='1',
    title='Xray Chest PA View',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='Radiology_Investigations',
    option_id='2',
    title='Xray Chest AP View',
    seq='2';
INSERT IGNORE INTO list_options SET list_id='Radiology_Investigations',
    option_id='3',
    title='Straight Xray Abdomen Erect Posture',
    seq='3';
INSERT IGNORE INTO list_options SET list_id='Radiology_Investigations',
    option_id='4',
    title='USG Whole Abdomen',
    seq='4';
INSERT IGNORE INTO list_options SET list_id='Radiology_Investigations',
    option_id='5',
    title='USG Upper Abdomen',
    seq='5';

