<?php
/*
 * The page shown when the user requests to see this form. Allows the user to edit form contents, and save. has a button for printing the saved form contents.
 */

/* for $GLOBALS[], ?? */
require_once('../../globals.php');
require_once($GLOBALS['srcdir'].'/api.inc');
/* for generate_form_field, ?? */
require_once($GLOBALS['srcdir'].'/options.inc.php');
/* note that we cannot include options_listadd.inc here, as it generates code before the <html> tag */

use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Core\Header;

/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_followup';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'Follow Up Form';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'followup';

/* Check the access control lists to ensure permissions to this page */
if (!AclMain::aclCheckCore('patients', 'med')) {
 die(text($form_name).': '.xlt("Access Denied"));
}
$thisauth_write_addonly=FALSE;
if ( AclMain::aclCheckCore('patients','med','',array('write','addonly') )) {
 $thisauth_write_addonly=TRUE;
}

if (!$thisauth_write_addonly)
  die(text($form_name).': '.xlt("Adding is not authorized"));
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
$submiturl = $GLOBALS['rootdir'].'/forms/'.$form_folder.'/save.php?mode=update&amp;return=encounter&amp;id='.$_GET['id'];
if ($_GET['mode']) {
 if ($_GET['mode']=='noencounter') {
 $submiturl = $GLOBALS['rootdir'].'/forms/'.$form_folder.'/save.php?mode=new&amp;return=show&amp;id='.$_GET['id'];
 $returnurl = 'show.php';
 }
}
else
{
 $returnurl = $GLOBALS['form_exit_url'];
}

/* remove the time-of-day from all date fields */
if ($xyzzy['last_enc'] != '') {
    $dateparts = explode(' ', $xyzzy['last_enc']);
    $xyzzy['last_enc'] = $dateparts[0];
}
if ($xyzzy['date_visit'] != '') {
    $dateparts = explode(' ', $xyzzy['date_visit']);
    $xyzzy['date_visit'] = $dateparts[0];
}
if ($xyzzy['app_date'] != '') {
    $dateparts = explode(' ', $xyzzy['app_date']);
    $xyzzy['app_date'] = $dateparts[0];
}

/* define check field functions. used for translating from fields to html viewable strings */

function chkdata_Date(&$record, $var) {
        return htmlspecialchars($record{"$var"},ENT_QUOTES);
}

function chkdata_Txt(&$record, $var) {
        return htmlspecialchars($record{"$var"},ENT_QUOTES);
}

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>

<!-- declare this document as being encoded in UTF-8 -->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" ></meta>

<!-- assets -->
<?php Header::setupHeader('datetime-picker'); ?>
<!-- Form Specific Stylesheet. -->
<link rel="stylesheet" href="../../forms/<?php echo $form_folder; ?>/style.css">

<script>
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
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_1' value='1' data-section="gen_info" checked="checked" />General Information</td></tr><tr><td><div id="gen_info" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td>
<span class="fieldlabel"><?php xl('Date of Last Encounter','e'); ?> (yyyy-mm-dd): </span>
</td><td>
   <input type='text' size='10' class='datepicker' name='last_enc' id='last_enc'
    value="<?php $result=chkdata_Date($xyzzy,'last_enc'); echo $result; ?>"
</td>
<td>
<span class="fieldlabel"><?php xl('Date of Follow up','e'); ?> (yyyy-mm-dd): </span>
</td><td>
   <input type='text' size='10' class='datepicker' name='date_visit' id='date_visit'
    value="<?php $result=chkdata_Date($xyzzy,'date_visit'); echo $result; ?>"
</td>
<!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Last Encounter Number','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['enc_number'], $xyzzy['enc_number']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Reason for follow up','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['reason_follow'], $xyzzy['reason_follow']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section gen_info -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_2' value='1' data-section="followup_proper" checked="checked" />Follow Up Proper</td></tr><tr><td><div id="followup_proper" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Diagnosis on last encounter','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['diag_last'], $xyzzy['diag_last']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Follow up Note','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['follow_note'], $xyzzy['follow_note']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Present Status','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['present_state'], $xyzzy['present_state']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Diagnosis Changed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['diag_change'], $xyzzy['diag_change']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('New Diagnosis','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['new_diag'], $xyzzy['new_diag']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Treatment Changed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['trt_change'], $xyzzy['trt_change']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('New Investigations','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['new_inv'], $xyzzy['new_inv']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Next Visit Needed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['next_visit'], $xyzzy['next_visit']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Appointment Done','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['app_done'], $xyzzy['app_done']); ?></td><td>
<span class="fieldlabel"><?php xl('Next Visit Date','e'); ?> (yyyy-mm-dd): </span>
</td><td>
   <input type='text' size='10' class='datepicker' name='app_date' id='app_date'
    value="<?php $result=chkdata_Date($xyzzy,'app_date'); echo $result; ?>"
</td>
<!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section followup_proper -->
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
<script>
// jQuery stuff to make the page a little easier to use

$(function () {
    $(".save").click(function() { top.restoreSession(); document.forms["<?php echo $form_folder; ?>"].submit(); });

<?php if ($returnurl == 'show.php') { ?>
    $(".dontsave").click(function() { location.href='<?php echo $returnurl; ?>'; });
<?php } else { ?>
    $(".dontsave").click(function() { parent.closeTab(window.name, false); });
<?php } ?>

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

    $('.datepicker').datetimepicker({
        <?php $datetimepicker_timepicker = false; ?>
        <?php $datetimepicker_showseconds = false; ?>
        <?php $datetimepicker_formatInput = false; ?>
        <?php require($GLOBALS['srcdir'] . '/js/xl/jquery-datetimepicker-2-5-4.js.php'); ?>
        <?php // can add any additional javascript settings to datetimepicker here; need to prepend first setting with a comma ?>
    });
    $('.datetimepicker').datetimepicker({
        <?php $datetimepicker_timepicker = true; ?>
        <?php $datetimepicker_showseconds = false; ?>
        <?php $datetimepicker_formatInput = false; ?>
        <?php require($GLOBALS['srcdir'] . '/js/xl/jquery-datetimepicker-2-5-4.js.php'); ?>
        <?php // can add any additional javascript settings to datetimepicker here; need to prepend first setting with a comma ?>
    });
});
</script>
</body>
</html>

