<?php
/*
 * The page shown when the user requests a new form. allows the user to enter form contents, and save.
 */

/* for $GLOBALS[], ?? */
require_once('../../globals.php');
/* for acl_check(), ?? */
require_once($GLOBALS['srcdir'].'/api.inc');
/* for generate_form_field, ?? */
require_once($GLOBALS['srcdir'].'/options.inc.php');
/* note that we cannot include options_listadd.inc here, as it generates code before the <html> tag */

/** CHANGE THIS name to the name of your form. **/
$form_name = 'History Form';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'history';

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
$submiturl = $GLOBALS['rootdir'].'/forms/'.$form_folder.'/save.php?mode=new&amp;return=encounter';
/* no get logic here */
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>

<!-- declare this document as being encoded in UTF-8 -->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" ></meta>

<!-- supporting javascript code -->
<!-- for dialog -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/dialog.js"></script>
<!-- For jquery, required by the save and discard buttons. -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/textformat.js"></script>

<!-- Global Stylesheet -->
<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css"/>
<!-- Form Specific Stylesheet. -->
<link rel="stylesheet" href="../../forms/<?php echo $form_folder; ?>/style.css" type="text/css"/>

<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot']; ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/dynarch_calendar_setup.js"></script>

<script type="text/javascript">
// this line is to assist the calendar text boxes
var mypcc = '<?php echo $GLOBALS['phone_country_code']; ?>';

<!-- a validator for all the fields expected in this form -->
function validate() {
  return true;
}

<!-- a callback for validating field contents. executed at submission time. -->
function submitme() {
 var f = document.forms[0];
 if (validate(f)) {
  top.restoreSession();
  f.submit();
 }
}

</script>



<title><?php echo htmlspecialchars('New '.$form_name); ?></title>

</head>
<body class="body_top">

<div id="title">
<a href="<?php echo $returnurl; ?>" onclick="top.restoreSession()">
<span class="title"><?php xl($form_name,'e'); ?></span>
<span class="back">(<?php xl('Back','e'); ?>)</span>
</a>
</div>

<form method="post" action="<?php echo $submiturl; ?>" id="<?php echo $form_folder; ?>"> 

<!-- Save/Cancel buttons -->
<div id="top_buttons" class="top_buttons">
<fieldset class="top_buttons">
<input type="button" class="save" value="<?php xl('Save','e'); ?>" />
<input type="button" class="dontsave" value="<?php xl('Don\'t Save','e'); ?>" />
</fieldset>
</div><!-- end top_buttons -->

<!-- container for the main body of the form -->
<div id="form_container">
<fieldset>

<!-- display the form's manual based fields -->
<table border='0' cellpadding='0' width='100%'>
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_1' value='1' data-section="patient_particulars" checked="checked" />Patient Particulars</td></tr><tr><td><div id="patient_particulars" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Patient Name','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pt_name'], ''); ?></td><td>
<span class="fieldlabel"><?php xl('Date of Visit','e'); ?> (yyyy-mm-dd): </span>
</td><td>
   <input type='text' size='10' name='date_visit' id='date_visit'
    value="<?php echo date('Y-m-d', time()); ?>"
    title="<?php xl('yyyy-mm-dd','e'); ?>"
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
   <img src='../../pic/show_calendar.gif' width='24' height='22'
    id='img_date_visit' alt='[?]' style='cursor:pointer'
    title="<?php xl('Click here to choose a date','e'); ?>" />
<script type="text/javascript">
Calendar.setup({inputField:'date_visit', ifFormat:'%Y-%m-%d', button:'img_date_visit'});
</script>
</td>
<!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Age','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pt_age'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Respondent','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pt_respo'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Relation to Patient','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pt_rel'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Demographics Complete','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pt_dem'], ''); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section patient_particulars -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_2' value='1' data-section="history_proper" checked="checked" />History Proper</td></tr><tr><td><div id="history_proper" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Chief Complaints','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['ch_comp'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Present History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pr_his'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Past History','e').':'; ?></td><td class='text data' colspan='3'><?php echo generate_form_field($manual_layouts['past_his'], ''); ?></td><!-- called consumeRows 414--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section history_proper -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_3' value='1' data-section="pers_his" checked="checked" />Personal History</td></tr><tr><td><div id="pers_his" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Sleep','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['sleep'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Appetite','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['appetite'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Addiction','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['addiction'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Bowel Habit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['bowel_habit'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Bladder Habit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['bladder_habit'], ''); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section pers_his -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_4' value='1' data-section="other_history" checked="checked" />Other History</td></tr><tr><td><div id="other_history" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Family History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['fam_his'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Socioeconomic History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['soc_his'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Treatment History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['trt_his'], ''); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section other_history -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_5' value='1' data-section="misc" checked="checked" />Miscellaneous</td></tr><tr><td><div id="misc" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Follow Up Needed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['next_visit'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Appointment Done','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['app_done'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td>
<span class="fieldlabel"><?php xl('Follow up date','e'); ?> (yyyy-mm-dd): </span>
</td><td>
   <input type='text' size='10' name='follow_date' id='follow_date'
    value="<?php echo date('Y-m-d', time()); ?>"
    title="<?php xl('yyyy-mm-dd','e'); ?>"
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
   <img src='../../pic/show_calendar.gif' width='24' height='22'
    id='img_follow_date' alt='[?]' style='cursor:pointer'
    title="<?php xl('Click here to choose a date','e'); ?>" />
<script type="text/javascript">
Calendar.setup({inputField:'follow_date', ifFormat:'%Y-%m-%d', button:'img_follow_date'});
</script>
</td>
<!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section misc -->
</table>

</fieldset>
</div> <!-- end form_container -->

<!-- Save/Cancel buttons -->
<div id="bottom_buttons" class="button_bar">
<fieldset>
<input type="button" class="save" value="<?php xl('Save','e'); ?>" />
<input type="button" class="dontsave" value="<?php xl('Don\'t Save','e'); ?>" />
</fieldset>
</div><!-- end bottom_buttons -->
</form>
<script type="text/javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $(".save").click(function() { top.restoreSession(); document.forms["<?php echo $form_folder; ?>"].submit(); });
    $(".dontsave").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/$returnurl"; ?>'; });

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

