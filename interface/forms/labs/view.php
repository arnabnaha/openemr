<?php
/*
 * The page shown when the user requests to see this form. Allows the user to edit form contents, and save. has a button for printing the saved form contents.
 */

/* for $GLOBALS[], ?? */
require_once('../../globals.php');
/* for acl_check(), ?? */
require_once($GLOBALS['srcdir'].'/api.inc');
/* for generate_form_field, ?? */
require_once($GLOBALS['srcdir'].'/options.inc.php');
/* note that we cannot include options_listadd.inc here, as it generates code before the <html> tag */

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

if (!$thisauth_write_addonly)
  die(text($form_name).': '.xlt("Adding is not authorized"));
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
$submiturl = $GLOBALS['rootdir'].'/forms/'.$form_folder.'/save.php?mode=update&amp;return=encounter&amp;id='.$_GET['id'];
if ($_GET['mode']) {
 if ($_GET['mode']=='noencounter') {
 $submiturl = $GLOBALS['rootdir'].'/forms/'.$form_folder.'/save.php?mode=new&amp;return=show&amp;id='.$_GET['id'];
 $returnurl = 'show.php';
 }
}
else
{
 $returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
}


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
<!-- For jquery, required by the save, discard, and print buttons. -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/textformat.js"></script>

<!-- Global Stylesheet -->
<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css"/>
<!-- Form Specific Stylesheet. -->
<link rel="stylesheet" href="../../forms/<?php echo $form_folder; ?>/style.css" type="text/css"/>



<script type="text/javascript">
// this line is to assist the calendar text boxes
var mypcc = '<?php echo $GLOBALS['phone_country_code']; ?>';

<!-- FIXME: this needs to detect access method, and construct a URL appropriately! -->
function PrintForm() {
    newwin = window.open("<?php echo $rootdir.'/forms/'.$form_folder.'/print.php?id='.$_GET['id']; ?>","print_<?php echo $form_name; ?>");
}

</script>
<title><?php echo htmlspecialchars('View '.$form_name); ?></title>

</head>
<body class="body_top">

<div id="title">
<a href="<?php echo $returnurl; ?>" onclick="top.restoreSession()">
<span class="title"><?php htmlspecialchars(xl($form_name,'e')); ?></span>
<span class="back">(<?php xl('Back','e'); ?>)</span>
</a>
</div>

<form method="post" action="<?php echo $submiturl; ?>" id="<?php echo $form_folder; ?>"> 

<!-- Save/Cancel buttons -->
<div id="top_buttons" class="top_buttons">
<fieldset class="top_buttons">
<input type="button" class="save" value="<?php xl('Save Changes','e'); ?>" />
<input type="button" class="dontsave" value="<?php xl('Don\'t Save Changes','e'); ?>" />
<input type="button" class="print" value="<?php xl('Print','e'); ?>" />
</fieldset>
</div><!-- end top_buttons -->

<!-- container for the main body of the form -->
<div id="form_container">
<fieldset>

<!-- display the form's manual based fields -->
<table border='0' cellpadding='0' width='100%'>
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_1' value='1' data-section="blood_investigations" checked="checked" />Blood Investigations</td></tr><tr><td><div id="blood_investigations" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('1.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['blood_one'], $xyzzy['blood_one']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('2.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['blood_two'], $xyzzy['blood_two']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('3.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['blood_three'], $xyzzy['blood_three']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('4.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['blood_four'], $xyzzy['blood_four']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('5.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['blood_five'], $xyzzy['blood_five']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section blood_investigations -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_2' value='1' data-section="radiology_investigations" checked="checked" />Radiological Investigations</td></tr><tr><td><div id="radiology_investigations" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('1.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['radio_one'], $xyzzy['radio_one']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('2.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['radio_two'], $xyzzy['radio_two']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('3.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['radio_three'], $xyzzy['radio_three']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('4.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['radio_four'], $xyzzy['radio_four']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('5.','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['radio_five'], $xyzzy['radio_five']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section radiology_investigations -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_3' value='1' data-section="report_status" checked="checked" />Report Status</td></tr><tr><td><div id="report_status" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Date of Report','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['date_report'], $xyzzy['date_report']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Reports Uploaded','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['report_upload'], $xyzzy['report_upload']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section report_status -->
</table>

</fieldset>
</div> <!-- end form_container -->

<!-- Save/Cancel buttons -->
<div id="bottom_buttons" class="button_bar">
<fieldset>
<input type="button" class="save" value="<?php xl('Save Changes','e'); ?>" />
<input type="button" class="dontsave" value="<?php xl('Don\'t Save Changes','e'); ?>" />
<input type="button" class="print" value="<?php xl('Print','e'); ?>" />
</fieldset>
</div><!-- end bottom_buttons -->
</form>
<script type="text/javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $(".save").click(function() { top.restoreSession(); document.forms["<?php echo $form_folder; ?>"].submit(); });
    $(".dontsave").click(function() { location.href='<?php echo $returnurl; ?>'; });
    $(".print").click(function() { PrintForm(); });
    
    $(".sectionlabel input").click( function() {
    	var section = $(this).attr("data-section");
		if ( $(this).attr('checked' ) ) {
			$("#"+section).show();
		} else {
			$("#"+section).hide();
		}
    });

    $(".sectionlabel input").attr( 'checked', 'checked' );
    $(".section").show();
});
</script>
</body>
</html>

