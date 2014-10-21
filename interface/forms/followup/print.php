<?php
/*
 * The page shown when the user requests to print this form. This page automatically prints itsself, and closes its parent browser window.
 */

/* for $GLOBALS[], ?? */
require_once('../../globals.php');
/* for acl_check(), ?? */
require_once($GLOBALS['srcdir'].'/api.inc');
/* for generate_form_field, ?? */
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

$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';

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
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/textformat.js"></script>

<!-- Global Stylesheet -->
<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css"/>
<!-- Form Specific Stylesheet. -->
<link rel="stylesheet" href="../../forms/<?php echo $form_folder; ?>/style.css" type="text/css"/>
<title><?php echo htmlspecialchars('Print '.$form_name); ?></title>

</head>
<body class="body_top">

<div class="print_date"><?php xl('Printed on ','e'); echo date('F d, Y', time()); ?></div>

<form method="post" id="<?php echo $form_folder; ?>" action="">
<div class="title"><?php xl($form_name,'e'); ?></div>

<!-- container for the main body of the form -->
<div id="print_form_container">
<fieldset>

<!-- display the form's manual based fields -->
<table border='0' cellpadding='0' width='100%'>
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_1' value='1' data-section="gen_info" checked="checked" />General Information</td></tr><tr><td><div id="print_gen_info" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td>
<span class="fieldlabel"><?php xl('Date of Last Encounter','e'); ?>: </span>
</td><td>
   <input type='text' size='10' name='last_enc' id='last_enc'
    value="<?php $result=chkdata_Date($xyzzy,'last_enc'); echo $result; ?>"
    />
</td>
<td>
<span class="fieldlabel"><?php xl('Date of Follow up','e'); ?>: </span>
</td><td>
   <input type='text' size='10' name='date_visit' id='date_visit'
    value="<?php $result=chkdata_Date($xyzzy,'date_visit'); echo $result; ?>"
    />
</td>
<!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Last Encounter Number','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['enc_number'], $xyzzy['enc_number']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Reason for follow up','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['reason_follow'], $xyzzy['reason_follow']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section gen_info -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_2' value='1' data-section="followup_proper" checked="checked" />Follow Up Proper</td></tr><tr><td><div id="print_followup_proper" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Diagnosis on last encounter','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['diag_last'], $xyzzy['diag_last']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Follow up Note','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['follow_note'], $xyzzy['follow_note']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Present Status','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['present_state'], $xyzzy['present_state']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Diagnosis Changed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['diag_change'], $xyzzy['diag_change']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('New Diagnosis','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['new_diag'], $xyzzy['new_diag']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Treatment Changed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['trt_change'], $xyzzy['trt_change']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('New Investigations','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['new_inv'], $xyzzy['new_inv']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Next Visit Needed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['next_visit'], $xyzzy['next_visit']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Appointment Done','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['app_done'], $xyzzy['app_done']); ?></td><td>
<span class="fieldlabel"><?php xl('Next Visit Date','e'); ?>: </span>
</td><td>
   <input type='text' size='10' name='app_date' id='app_date'
    value="<?php $result=chkdata_Date($xyzzy,'app_date'); echo $result; ?>"
    />
</td>
<!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section followup_proper -->
</table>


</fieldset>
</div><!-- end print_form_container -->

</form>
<script type="text/javascript">
window.print();
window.close();
</script>
</body>
</html>

