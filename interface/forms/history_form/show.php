<?php
/*
 * The page shown when the user requests to see this form in a "report view". does not allow editing contents, or saving. has 'print' and 'delete' buttons.
 */

/* for $GLOBALS[], ?? */
require_once('../../globals.php');
/* for acl_check(), ?? */
require_once($GLOBALS['srcdir'].'/api.inc');
/* for display_layout_rows(), ?? */
require_once($GLOBALS['srcdir'].'/options.inc.php');

/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_history';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'History Form';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'history_form';

/* Check the access control lists to ensure permissions to this page */
if (!acl_check('patients', 'med')) {
 die(text($form_name).': '.xlt("Access Denied"));
}
$thisauth_write_addonly=FALSE;
if ( acl_check('patients','med','',array('write','addonly') )) {
 $thisauth_write_addonly=TRUE;
}

/* perform a squad check for pages touching patients, if we're in 'athletic team' mode */
if ($GLOBALS['athletic_team']!='false') {
  $tmp = getPatientData($pid, 'squad');
  if ($tmp['squad'] && ! acl_check('squads', $tmp['squad']))
   die(text($form_name).': '.xlt("Access Denied"));
}
/* Use the formFetch function from api.inc to load the saved record */
$xyzzy = formFetch($table_name, $_GET['id']);

/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
$manual_layouts = array( 
 'pt_name' => 
   array( 'field_id' => 'pt_name',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'date_visit' => 
   array( 'field_id' => 'date_visit',
          'data_type' => '4',
          'fld_length' => '0',
          'description' => '',
          'list_id' => '' ),
 'pt_age' => 
   array( 'field_id' => 'pt_age',
          'data_type' => '2',
          'fld_length' => '10',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'pt_respo' => 
   array( 'field_id' => 'pt_respo',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Respondent' ),
 'pt_rel' => 
   array( 'field_id' => 'pt_rel',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Relationship_list' ),
 'pt_dem' => 
   array( 'field_id' => 'pt_dem',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
 'ch_comp' => 
   array( 'field_id' => 'ch_comp',
          'data_type' => '3',
          'fld_length' => '50',
          'max_length' => '4',
          'description' => '',
          'list_id' => '' ),
 'pr_his' => 
   array( 'field_id' => 'pr_his',
          'data_type' => '3',
          'fld_length' => '50',
          'max_length' => '4',
          'description' => '',
          'list_id' => '' ),
 'past_his' => 
   array( 'field_id' => 'past_his',
          'data_type' => '3',
          'fld_length' => '50',
          'max_length' => '4',
          'description' => '',
          'list_id' => '' ),
 'sleep' => 
   array( 'field_id' => 'sleep',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'appetite' => 
   array( 'field_id' => 'appetite',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'addiction' => 
   array( 'field_id' => 'addiction',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'addiction_status' ),
 'bowel_habit' => 
   array( 'field_id' => 'bowel_habit',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'bladder_habit' => 
   array( 'field_id' => 'bladder_habit',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'fam_his' => 
   array( 'field_id' => 'fam_his',
          'data_type' => '21',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'hist_take' ),
 'soc_his' => 
   array( 'field_id' => 'soc_his',
          'data_type' => '21',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'hist_take' ),
 'trt_his' => 
   array( 'field_id' => 'trt_his',
          'data_type' => '3',
          'fld_length' => '60',
          'max_length' => '4',
          'description' => '',
          'list_id' => '' ),
 'next_visit' => 
   array( 'field_id' => 'next_visit',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
 'app_done' => 
   array( 'field_id' => 'app_done',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
 'follow_date' => 
   array( 'field_id' => 'follow_date',
          'data_type' => '4',
          'fld_length' => '0',
          'description' => '',
          'list_id' => '' )
 );

/* since we have no-where to return, abuse returnurl to link to the 'edit' page */
/* FIXME: pass the ID, create blank rows if necissary. */
$returnurl = "../../forms/$form_folder/view.php?mode=noencounter";

/* remove the time-of-day from all date fields */
if ($xyzzy['date_visit'] != '') {
    $dateparts = split(' ', $xyzzy['date_visit']);
    $xyzzy['date_visit'] = $dateparts[0];
}
if ($xyzzy['follow_date'] != '') {
    $dateparts = split(' ', $xyzzy['follow_date']);
    $xyzzy['follow_date'] = $dateparts[0];
}

/* define check field functions. used for translating from fields to html viewable strings */

function chkdata_Date(&$record, $var) {
        return htmlspecialchars($record{"$var"},ENT_QUOTES);
}

function chkdata_Txt(&$record, $var) {
        return htmlspecialchars($record{"$var"},ENT_QUOTES);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>

<!-- declare this document as being encoded in UTF-8 -->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" ></meta>

<!-- supporting javascript code -->
<!-- for dialog -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/dialog.js"></script>
<!-- For jquery, required by edit, print, and delete buttons. -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/textformat.js"></script>

<!-- Global Stylesheet -->
<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css"/>
<!-- Form Specific Stylesheet. -->
<link rel="stylesheet" href="../../forms/<?php echo $form_folder; ?>/style.css" type="text/css"/>

<script type="text/javascript">

<!-- FIXME: this needs to detect access method, and construct a URL appropriately! -->
function PrintForm() {
    newwin = window.open("<?php echo $rootdir.'/forms/'.$form_folder.'/print.php?id='.$_GET['id']; ?>","print_<?php echo $form_name; ?>");
}

</script>
<title><?php echo htmlspecialchars('Show '.$form_name); ?></title>

</head>
<body class="body_top">

<div id="title">
<span class="title"><?php xl($form_name,'e'); ?></span>
<?php
 if ($thisauth_write_addonly)
  { ?>
<a href="<?php echo $returnurl; ?>" onclick="top.restoreSession()">
<span class="back"><?php xl($tmore,'e'); ?></span>
</a>
<?php }; ?>
</div>

<form method="post" id="<?php echo $form_folder; ?>" action="">

<!-- container for the main body of the form -->
<div id="form_container">

<div id="show">

<!-- display the form's manual based fields -->
<table border='0' cellpadding='0' width='100%'>
<tr><td class='sectionlabel'>Patient Particulars</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Patient Name','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['pt_name'], $xyzzy['pt_name']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Date of Visit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['date_visit'], $xyzzy['date_visit']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Age','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['pt_age'], $xyzzy['pt_age']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Respondent','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['pt_respo'], $xyzzy['pt_respo']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Relation to Patient','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['pt_rel'], $xyzzy['pt_rel']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Demographics Complete','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['pt_dem'], $xyzzy['pt_dem']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields)0--></tr>
<tr><td class='sectionlabel'>History Proper</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Chief Complaints','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['ch_comp'], $xyzzy['ch_comp']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Present History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['pr_his'], $xyzzy['pr_his']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Past History','e').':'; ?></td><td class='text data' colspan='3'><?php echo generate_display_field($manual_layouts['past_his'], $xyzzy['past_his']); ?></td><!-- called consumeRows 414--> <!-- Exiting not($fields)0--></tr>
<tr><td class='sectionlabel'>Personal History</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Sleep','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['sleep'], $xyzzy['sleep']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Appetite','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['appetite'], $xyzzy['appetite']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Addiction','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['addiction'], $xyzzy['addiction']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Bowel Habit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['bowel_habit'], $xyzzy['bowel_habit']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Bladder Habit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['bladder_habit'], $xyzzy['bladder_habit']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
<tr><td class='sectionlabel'>Other History</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Family History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['fam_his'], $xyzzy['fam_his']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Socioeconomic History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['soc_his'], $xyzzy['soc_his']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Treatment History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['trt_his'], $xyzzy['trt_his']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
<tr><td class='sectionlabel'>Miscellaneous</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Follow Up Needed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['next_visit'], $xyzzy['next_visit']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Appointment Done','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['app_done'], $xyzzy['app_done']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Follow up date','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['follow_date'], $xyzzy['follow_date']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
</table>


</div><!-- end show -->

</div><!-- end form_container -->

<!-- Print button -->
<div id="button_bar" class="button_bar">
<fieldset class="button_bar">
<input type="button" class="print" value="<?php xl('Print','e'); ?>" />
</fieldset>
</div><!-- end button_bar -->

</form>
<script type="text/javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $(".print").click(function() { PrintForm(); });
});
</script>
</body>
</html>

