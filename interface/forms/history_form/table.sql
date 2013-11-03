CREATE TABLE IF NOT EXISTS `form_history` (
    /* both extended and encounter forms need a last modified date */
    date datetime default NULL comment 'last modified date',
    /* these fields are common to all encounter forms. */
    id bigint(20) NOT NULL auto_increment,
    pid bigint(20) NOT NULL default 0,
    user varchar(255) default NULL,
    groupname varchar(255) default NULL,
    authorized tinyint(4) default NULL,
    activity tinyint(4) default NULL,
    pt_name varchar(255),
    date_visit datetime default NULL,
    pt_age varchar(255),
    pt_respo varchar(255),
    pt_rel varchar(255),
    pt_dem varchar(255),
    ch_comp TEXT,
    pr_his TEXT,
    past_his TEXT,
    sleep varchar(255),
    appetite varchar(255),
    addiction varchar(255),
    bowel_habit varchar(255),
    bladder_habit varchar(255),
    fam_his varchar(255),
    soc_his varchar(255),
    trt_his TEXT,
    next_visit varchar(255),
    app_done varchar(255),
    follow_date datetime default NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='addiction_status',
    title='Addiction Status';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='1',
    title='Smoker for 1 to 5 years',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='2',
    title='Smoker for 6 to 10 years',
    seq='2';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='3',
    title='Smoker for 11 to 15 years',
    seq='3';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='4',
    title='Smoker for 16 to 20 years',
    seq='4';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='5',
    title='Smoker for 21 to 25 years',
    seq='5';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='6',
    title='Smoker for more than 25 years',
    seq='6';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='7',
    title='Alcohol occassionally',
    seq='7';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='8',
    title='Alcohol frequently',
    seq='8';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='9',
    title='Alcohol for more than 8 years',
    seq='9';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='10',
    title='Beetel Leaf',
    seq='10';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='11',
    title='Chewing Tobacco',
    seq='11';
INSERT IGNORE INTO list_options SET list_id='addiction_status',
    option_id='12',
    title='No Addiction',
    seq='12';
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='hist_take',
    title='History Taken';
INSERT IGNORE INTO list_options SET list_id='hist_take',
    option_id='1',
    title='In Demographics History Section',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='hist_take',
    option_id='2',
    title='Not In Demographics History Section',
    seq='2';
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='Respondent',
    title='Respondent';
INSERT IGNORE INTO list_options SET list_id='Respondent',
    option_id='1',
    title='Patient',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='Respondent',
    option_id='2',
    title='Patient Party',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='Relationship_list',
    title='Relationship List';
INSERT IGNORE INTO list_options SET list_id='Relationship_list',
    option_id='1',
    title='Self',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='Relationship_list',
    option_id='2',
    title='Father',
    seq='2';
INSERT IGNORE INTO list_options SET list_id='Relationship_list',
    option_id='3',
    title='Mother',
    seq='3';
INSERT IGNORE INTO list_options SET list_id='Relationship_list',
    option_id='4',
    title='Brother',
    seq='4';
INSERT IGNORE INTO list_options SET list_id='Relationship_list',
    option_id='5',
    title='Sister',
    seq='5';
INSERT IGNORE INTO list_options SET list_id='Relationship_list',
    option_id='6',
    title='Cousin',
    seq='6';
INSERT IGNORE INTO list_options SET list_id='Relationship_list',
    option_id='7',
    title='Relatives',
    seq='7';
INSERT IGNORE INTO list_options SET list_id='Relationship_list',
    option_id='8',
    title='Uncle',
    seq='8';
INSERT IGNORE INTO list_options SET list_id='Relationship_list',
    option_id='9',
    title='Aunt',
    seq='9';

