CREATE TABLE IF NOT EXISTS `form_gensurvey` (
    /* both extended and encounter forms need a last modified date */
    date datetime default NULL comment 'last modified date',
    /* these fields are common to all encounter forms. */
    id bigint(20) NOT NULL auto_increment,
    pid bigint(20) NOT NULL default 0,
    user varchar(255) default NULL,
    groupname varchar(255) default NULL,
    authorized tinyint(4) default NULL,
    activity tinyint(4) default NULL,
    pts_name varchar(255),
    gens_date datetime default NULL,
    genu_age varchar(255),
    geny_examined int(11) default NULL,
    genr_higher varchar(255),
    fc_facies varchar(255),
    bu_build varchar(255),
    nu_nutri varchar(255),
    decu_gen varchar(255),
    sur_pal varchar(255),
    cya_cyanosis varchar(255),
    cl_clubbing varchar(255),
    gr_clubgrade varchar(255),
    ic_icterus varchar(255),
    oe_oedema varchar(255),
    loc_locoedema varchar(255),
    vit_vitals varchar(255),
    no_node varchar(255),
    ne_vein varchar(255),
    sk_skin varchar(255),
    sku_skull varchar(255),
    ex_extra varchar(255),
    imp_impression varchar(255),
    PRIMARY KEY (id)
) ENGINE=InnoDB;
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='build_list',
    title='Build List';
INSERT IGNORE INTO list_options SET list_id='build_list',
    option_id='1',
    title='Healthy',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='build_list',
    option_id='2',
    title='Average',
    seq='2';
INSERT IGNORE INTO list_options SET list_id='build_list',
    option_id='3',
    title='Lean',
    seq='3';
INSERT IGNORE INTO list_options SET list_id='build_list',
    option_id='4',
    title='Cachexic',
    seq='4';
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='decubitus_list',
    title='Decubitus List';
INSERT IGNORE INTO list_options SET list_id='decubitus_list',
    option_id='1',
    title='Of Choice',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='decubitus_list',
    option_id='2',
    title='Sitting',
    seq='2';
INSERT IGNORE INTO list_options SET list_id='decubitus_list',
    option_id='3',
    title='Propped Up',
    seq='3';
INSERT IGNORE INTO list_options SET list_id='decubitus_list',
    option_id='4',
    title='Right Lateral',
    seq='4';
INSERT IGNORE INTO list_options SET list_id='decubitus_list',
    option_id='5',
    title='Left Lateral',
    seq='5';
INSERT IGNORE INTO list_options SET list_id='decubitus_list',
    option_id='6',
    title='Restless',
    seq='6';
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='clubbing_grades',
    title='Clubbing Grades';
INSERT IGNORE INTO list_options SET list_id='clubbing_grades',
    option_id='1',
    title='First Degree',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='clubbing_grades',
    option_id='2',
    title='Second Degree',
    seq='2';
INSERT IGNORE INTO list_options SET list_id='clubbing_grades',
    option_id='3',
    title='Third Degree',
    seq='3';
INSERT IGNORE INTO list_options SET list_id='clubbing_grades',
    option_id='4',
    title='Fourth Degree',
    seq='4';
INSERT IGNORE INTO list_options SET list_id='lists',
    option_id='oedema_location',
    title='Oedema Location';
INSERT IGNORE INTO list_options SET list_id='oedema_location',
    option_id='1',
    title='Pedal Unilateral Dipping',
    seq='1';
INSERT IGNORE INTO list_options SET list_id='oedema_location',
    option_id='2',
    title='Pedal Bilateral Dipping',
    seq='2';
INSERT IGNORE INTO list_options SET list_id='oedema_location',
    option_id='3',
    title='Pedal Unilateral Non dipping',
    seq='3';
INSERT IGNORE INTO list_options SET list_id='oedema_location',
    option_id='4',
    title='Pedal Bilateral Non dipping',
    seq='4';
INSERT IGNORE INTO list_options SET list_id='oedema_location',
    option_id='5',
    title='Parietal',
    seq='5';
INSERT IGNORE INTO list_options SET list_id='oedema_location',
    option_id='6',
    title='Facial',
    seq='6';

