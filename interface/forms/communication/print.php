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
$table_name = 'form_communication';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'Communication Log';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'communication';

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
 'contact_date' => 
   array( 'field_id' => 'contact_date',
          'data_type' => '4',
          'fld_length' => '0',
          'description' => 'Date contact occured/was attempted',
          'list_id' => '' ),
 'contact_name' => 
   array( 'field_id' => 'contact_name',
          'data_type' => '2',
          'fld_length' => '10',
          'max_length' => '255',
          'description' => 'Person we are attempting to contact/were contacted by',
          'list_id' => '' ),
 'phone' => 
   array( 'field_id' => 'phone',
          'data_type' => '2',
          'fld_length' => '10',
          'max_length' => '15',
          'description' => 'Phone number dialed or number of caller(if known)',
          'list_id' => '' ),
 'direction' => 
   array( 'field_id' => 'direction',
          'data_type' => '21',
          'fld_length' => '0',
          'description' => 'Was the call outbound?',
          'list_id' => 'yesno' ),
 'contact_success' => 
   array( 'field_id' => 'contact_success',
          'data_type' => '21',
          'fld_length' => '0',
          'description' => 'If the call was outbound, did you get ahold of someone who could help?',
          'list_id' => 'yesno' ),
 'reason' => 
   array( 'field_id' => 'reason',
          'data_type' => '3',
          'fld_length' => '10',
          'max_length' => '3',
          'description' => 'the principal reason or reasons contact was attempted',
          'list_id' => '' ),
 'result' => 
   array( 'field_id' => 'result',
          'data_type' => '3',
          'fld_length' => '10',
          'max_length' => '3',
          'description' => '',
          'list_id' => '' ),
 'screener' => 
   array( 'field_id' => 'screener',
          'data_type' => '10',
          'fld_length' => '0',
          'description' => 'Staff Member',
          'list_id' => '' ),
 'signature_box' => 
   array( 'field_id' => 'signature_box',
          'data_type' => '2',
          'fld_length' => '10',
          'max_length' => '60',
          'description' => 'Sign here to signify all information in this form is correct',
          'list_id' => '' )
 );

$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';

if ($xyzzy['contact_date'] != '') {
    $dateparts = split(' ', $xyzzy['contact_date']);
    $xyzzy['contact_date'] = $dateparts[0];
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
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_1' value='1' data-section="contact" checked="checked" />Who</td></tr><tr><td><div id="print_contact" class='section'><table>
<!-- called consumeRows 014--> <!--  generating 4 cells and calling --><td>
<span class="fieldlabel"><?php xl('Date','e'); ?>: </span>
</td><td>
   <input type='text' size='10' name='contact_date' id='contact_date' title='Date contact occured/was attempted'
    value="<?php $result=chkdata_Date($xyzzy,'contact_date'); echo $result; ?>"
    />
</td>
<!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Name','e').':'; ?></td><td class='text data' colspan='3'><?php echo generate_form_field($manual_layouts['contact_name'], $xyzzy['contact_name']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Phone #','e').':'; ?></td><td class='text data' colspan='3'><?php echo generate_form_field($manual_layouts['phone'], $xyzzy['phone']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Outgoing Call?','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['direction'], $xyzzy['direction']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Contact Successful?','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['contact_success'], $xyzzy['contact_success']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Reasons for contact','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['reason'], $xyzzy['reason']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Results of conversation','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['result'], $xyzzy['result']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section contact -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_2' value='1' data-section="staff" checked="checked" />Agency Representitive</td></tr><tr><td><div id="print_staff" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Access Screener','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['screener'], $xyzzy['screener']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Signature','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['signature_box'], $xyzzy['signature_box']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section staff -->
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

