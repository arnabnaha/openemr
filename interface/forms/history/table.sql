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
    ref_need varchar(255),
    ref_name varchar(255),
    ref_doc varchar(255),
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
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='Referring_Speciality',
    title='Referring Speciality';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='1',
    title='Waiting for drug response/patient response',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='2',
    title='Refer on next visit',
    seq='2';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='3',
    title='Cardiologist',
    seq='3';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='4',
    title='Medicine',
    seq='4';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='5',
    title='Surgeon',
    seq='5';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='6',
    title='Psychiatrist',
    seq='6';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='7',
    title='Gastro Enterologistr',
    seq='7';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='8',
    title='Endocrinologist',
    seq='8';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='9',
    title='Diabetician',
    seq='9';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='10',
    title='Neurologist',
    seq='10';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='11',
    title='Dermatologist',
    seq='11';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='12',
    title='Cardio Thoracic Vascular Surgeon',
    seq='12';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='13',
    title='Paediatrician',
    seq='13';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='14',
    title='Paediatric Surgeon',
    seq='14';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='15',
    title='Gynaecologist',
    seq='15';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='16',
    title='Neurootologist',
    seq='16';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='17',
    title='ENT Speacialist',
    seq='17';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='18',
    title='Ophthalmologist',
    seq='18';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='19',
    title='Dietician',
    seq='19';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='20',
    title='Orthpaedic Surgeon',
    seq='20';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='21',
    title='Physiotherapist',
    seq='21';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='22',
    title='Oncologist',
    seq='22';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='23',
    title='Onco Surgeon',
    seq='23';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='24',
    title='Neuro Surgeon',
    seq='24';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='25',
    title='Gastro Surgeon',
    seq='25';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='26',
    title='Plastic Surgeon',
    seq='26';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='27',
    title='Physical Medicine and Rehabilitation',
    seq='27';
INSERT IGNORE INTO list_options SET list_id='Referring_Speciality',
    option_id='28',
    title='Speech Therapist',
    seq='28';

