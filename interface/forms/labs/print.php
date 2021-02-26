<?php
/*
 * The page shown when the user requests to print this form. This page automatically prints itsself, and closes its parent browser window.
 */

/* for $GLOBALS[], ?? */
require_once('../../globals.php');
require_once($GLOBALS['srcdir'].'/api.inc');
/* for generate_form_field, ?? */
require_once($GLOBALS['srcdir'].'/options.inc.php');

use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Core\Header;

/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_labs';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'Investigation Orders';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'labs';

/* Check the access control lists to ensure permissions to this page */
if (!AclMain::aclCheckCore('patients', 'med')) {
 die(text($form_name).': '.xlt("Access Denied"));
}
$thisauth_write_addonly=FALSE;
if ( AclMain::aclCheckCore('patients','med','',array('write','addonly') )) {
 $thisauth_write_addonly=TRUE;
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

$returnurl = 'encounter_top.php';


/* define check field functions. used for translating from fields to html viewable strings */

function chkdata_Txt(&$record, $var) {
        return htmlspecialchars($record{"$var"},ENT_QUOTES);
}

?><!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>

<!-- declare this document as being encoded in UTF-8 -->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" ></meta>

<!-- assets -->
<?php Header::setupHeader(); ?>
<!-- Form Specific Stylesheet. -->
<link rel="stylesheet" href="../../forms/<?php echo $form_folder; ?>/style.css">
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
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_1' value='1' data-section="blood_investigations" checked="checked" />Blood Investigations</td></tr><tr><td><div id="print_blood_investigations" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('1.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['blood_one'], $xyzzy['blood_one']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('2.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['blood_two'], $xyzzy['blood_two']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('3.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['blood_three'], $xyzzy['blood_three']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('4.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['blood_four'], $xyzzy['blood_four']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('5.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['blood_five'], $xyzzy['blood_five']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section blood_investigations -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_2' value='1' data-section="radiology_investigations" checked="checked" />Radiological Investigations</td></tr><tr><td><div id="print_radiology_investigations" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('1.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['radio_one'], $xyzzy['radio_one']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('2.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['radio_two'], $xyzzy['radio_two']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('3.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['radio_three'], $xyzzy['radio_three']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('4.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['radio_four'], $xyzzy['radio_four']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('5.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['radio_five'], $xyzzy['radio_five']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section radiology_investigations -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_3' value='1' data-section="report_status" checked="checked" />Report Status</td></tr><tr><td><div id="print_report_status" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Date of Report','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['date_report'], $xyzzy['date_report']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Reports Uploaded','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['report_upload'], $xyzzy['report_upload']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section report_status -->
</table>


</fieldset>
</div><!-- end print_form_container -->

</form>
<script>
window.print();
window.close();
</script>
</body>
</html>

