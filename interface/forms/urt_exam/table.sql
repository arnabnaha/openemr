CREATE TABLE IF NOT EXISTS `form_urt` (
    /* both extended and encounter forms need a last modified date */
    date datetime default NULL comment 'last modified date',
    /* these fields are common to all encounter forms. */
    id bigint(20) NOT NULL auto_increment,
    pid bigint(20) NOT NULL default 0,
    user varchar(255) default NULL,
    groupname varchar(255) default NULL,
    authorized tinyint(4) default NULL,
    activity tinyint(4) default NULL,
    inspect_gen varchar(255),
    inspect_lip varchar(255),
    inspect_teeth varchar(255),
    inspect_tongue varchar(255),
    inspect_oral varchar(255),
    inspect_posterior varchar(255),
    inspect_tonsil varchar(255),
    inspect_gum varchar(255),
    inspect_vestibule varchar(255),
    inspect_uvula varchar(255),
    palp_glands varchar(255),
    palp_neck varchar(255),
    palp_extra TEXT,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

