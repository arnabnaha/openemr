CREATE TABLE IF NOT EXISTS `form_communication` (
    /* both extended and encounter forms need a last modified date */
    date datetime default NULL comment 'last modified date',
    /* these fields are common to all encounter forms. */
    id bigint(20) NOT NULL auto_increment,
    pid bigint(20) NOT NULL default 0,
    user varchar(255) default NULL,
    groupname varchar(255) default NULL,
    authorized tinyint(4) default NULL,
    activity tinyint(4) default NULL,
    contact_date datetime default NULL,
    contact_name varchar(255),
    phone varchar(15),
    direction varchar(255),
    contact_success varchar(255),
    reason TEXT,
    result TEXT,
    screener int(11) default NULL,
    signature_box varchar(60),
    PRIMARY KEY (id)
) TYPE=InnoDB;

