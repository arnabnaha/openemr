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
$table_name = 'form_followup';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'Follow Up Form';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'followup';

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
 'last_enc' => 
   array( 'field_id' => 'last_enc',
          'data_type' => '4',
          'fld_length' => '0',
          'description' => '',
          'list_id' => '' ),
 'date_visit' => 
   array( 'field_id' => 'date_visit',
          'data_type' => '4',
          'fld_length' => '0',
          'description' => '',
          'list_id' => '' ),
 'enc_number' => 
   array( 'field_id' => 'enc_number',
          'data_type' => '2',
          'fld_length' => '20',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'reason_follow' => 
   array( 'field_id' => 'reason_follow',
          'data_type' => '21',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'reas_follow' ),
 'diag_last' => 
   array( 'field_id' => 'diag_last',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'follow_note' => 
   array( 'field_id' => 'follow_note',
          'data_type' => '3',
          'fld_length' => '50',
          'max_length' => '4',
          'description' => '',
          'list_id' => '' ),
 'present_state' => 
   array( 'field_id' => 'present_state',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'outcome' ),
 'diag_change' => 
   array( 'field_id' => 'diag_change',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
 'new_diag' => 
   array( 'field_id' => 'new_diag',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'trt_change' => 
   array( 'field_id' => 'trt_change',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
 'new_inv' => 
   array( 'field_id' => 'new_inv',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
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
 'app_date' => 
   array( 'field_id' => 'app_date',
          'data_type' => '4',
          'fld_length' => '0',
          'description' => '',
          'list_id' => '' )
 );

/* since we have no-where to return, abuse returnurl to link to the 'edit' page */
/* FIXME: pass the ID, create blank rows if necissary. */
$returnurl = "../../forms/$form_folder/view.php?mode=noencounter";

/* remove the time-of-day from all date fields */
if ($xyzzy['last_enc'] != '') {
    $dateparts = split(' ', $xyzzy['last_enc']);
    $xyzzy['last_enc'] = $dateparts[0];
}
if ($xyzzy['date_visit'] != '') {
    $dateparts = split(' ', $xyzzy['date_visit']);
    $xyzzy['date_visit'] = $dateparts[0];
}
if ($xyzzy['app_date'] != '') {
    $dateparts = split(' ', $xyzzy['app_date']);
    $xyzzy['app_date'] = $dateparts[0];
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
<tr><td class='sectionlabel'>General Information</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Date of Last Encounter','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['last_enc'], $xyzzy['last_enc']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Date of Follow up','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['date_visit'], $xyzzy['date_visit']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Last Encounter Number','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['enc_number'], $xyzzy['enc_number']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Reason for follow up','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['reason_follow'], $xyzzy['reason_follow']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields)0--></tr>
<tr><td class='sectionlabel'>Follow Up Proper</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Diagnosis on last encounter','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['diag_last'], $xyzzy['diag_last']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Follow up Note','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['follow_note'], $xyzzy['follow_note']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Present Status','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['present_state'], $xyzzy['present_state']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Diagnosis Changed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['diag_change'], $xyzzy['diag_change']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('New Diagnosis','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['new_diag'], $xyzzy['new_diag']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Treatment Changed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['trt_change'], $xyzzy['trt_change']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('New Investigations','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['new_inv'], $xyzzy['new_inv']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Next Visit Needed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['next_visit'], $xyzzy['next_visit']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Appointment Done','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['app_done'], $xyzzy['app_done']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Next Visit Date','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['app_date'], $xyzzy['app_date']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields)0--></tr>
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

