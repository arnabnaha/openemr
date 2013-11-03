CREATE TABLE IF NOT EXISTS `form_respexam` (
    /* both extended and encounter forms need a last modified date */
    date datetime default NULL comment 'last modified date',
    /* these fields are common to all encounter forms. */
    id bigint(20) NOT NULL auto_increment,
    pid bigint(20) NOT NULL default 0,
    user varchar(255) default NULL,
    groupname varchar(255) default NULL,
    authorized tinyint(4) default NULL,
    activity tinyint(4) default NULL,
    chest_shape varchar(255),
    chest_scar varchar(255),
    ven_prom varchar(255),
    sym_mov varchar(255),
    drop_shoulder varchar(255),
    chest_suck varchar(255),
    chest_full varchar(255),
    trac_shift varchar(255),
    apex_beat varchar(255),
    full_insp varchar(255),
    full_exp varchar(255),
    chest_movement varchar(255),
    voc_fremitus varchar(255),
    per_front varchar(255),
    per_axilla varchar(255),
    per_back varchar(255),
    per_apex varchar(255),
    per_clavicle varchar(255),
    per_sternum varchar(255),
    voc_resonanace varchar(255),
    breath_sound varchar(255),
    ad_sound varchar(255),
    PRIMARY KEY (id)
) ENGINE=InnoDB;
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='present_absent',
    title='Present Absent';
INSERT IGNORE INTO list_options SET list_id='present_absent',
    option_id='1',
    title='Present',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='present_absent',
    option_id='2',
    title='Absent',
    seq='2';

