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
$table_name = 'form_labs';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'Investigation Orders';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'labs';

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
 'blood_one' => 
   array( 'field_id' => 'blood_one',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Blood_Investigations' ),
 'blood_two' => 
   array( 'field_id' => 'blood_two',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Blood_Investigations' ),
 'blood_three' => 
   array( 'field_id' => 'blood_three',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Blood_Investigations' ),
 'blood_four' => 
   array( 'field_id' => 'blood_four',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Blood_Investigations' ),
 'blood_five' => 
   array( 'field_id' => 'blood_five',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Blood_Investigations' ),
 'radio_one' => 
   array( 'field_id' => 'radio_one',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Radiology_Investigations' ),
 'radio_two' => 
   array( 'field_id' => 'radio_two',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Radiology_Investigations' ),
 'radio_three' => 
   array( 'field_id' => 'radio_three',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Radiology_Investigations' ),
 'radio_four' => 
   array( 'field_id' => 'radio_four',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Radiology_Investigations' ),
 'radio_five' => 
   array( 'field_id' => 'radio_five',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Radiology_Investigations' ),
 'date_report' => 
   array( 'field_id' => 'date_report',
          'data_type' => '2',
          'fld_length' => '20',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'report_upload' => 
   array( 'field_id' => 'report_upload',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' )
 );

/* since we have no-where to return, abuse returnurl to link to the 'edit' page */
/* FIXME: pass the ID, create blank rows if necissary. */
$returnurl = "../../forms/$form_folder/view.php?mode=noencounter";


/* define check field functions. used for translating from fields to html viewable strings */

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
<tr><td class='sectionlabel'>Blood Investigations</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('1.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['blood_one'], $xyzzy['blood_one']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('2.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['blood_two'], $xyzzy['blood_two']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('3.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['blood_three'], $xyzzy['blood_three']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('4.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['blood_four'], $xyzzy['blood_four']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('5.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['blood_five'], $xyzzy['blood_five']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
<tr><td class='sectionlabel'>Radiological Investigations</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('1.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['radio_one'], $xyzzy['radio_one']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('2.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['radio_two'], $xyzzy['radio_two']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('3.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['radio_three'], $xyzzy['radio_three']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('4.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['radio_four'], $xyzzy['radio_four']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('5.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['radio_five'], $xyzzy['radio_five']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
<tr><td class='sectionlabel'>Report Status</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Date of Report','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['date_report'], $xyzzy['date_report']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Reports Uploaded','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['report_upload'], $xyzzy['report_upload']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields)0--></tr>
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

