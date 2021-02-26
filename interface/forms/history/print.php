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
$table_name = 'form_history';

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
          'list_id' => '' ),
 'ref_need' => 
   array( 'field_id' => 'ref_need',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
 'ref_name' => 
   array( 'field_id' => 'ref_name',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'ref_doc' => 
   array( 'field_id' => 'ref_doc',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Referring_Speciality' )
 );

$returnurl = 'encounter_top.php';

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
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/dialog.js?v=<?php echo $v_js_includes; ?>"></script>
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
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_1' value='1' data-section="patient_particulars" checked="checked" />Patient Particulars</td></tr><tr><td><div id="print_patient_particulars" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Patient Name','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pt_name'], $xyzzy['pt_name']); ?></td><td>
<span class="fieldlabel"><?php xl('Date of Visit','e'); ?>: </span>
</td><td>
   <input type='text' size='10' name='date_visit' id='date_visit'
    value="<?php $result=chkdata_Date($xyzzy,'date_visit'); echo $result; ?>"
    />
</td>
<!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Age','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pt_age'], $xyzzy['pt_age']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Respondent','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pt_respo'], $xyzzy['pt_respo']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Relation to Patient','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pt_rel'], $xyzzy['pt_rel']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Demographics Complete','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pt_dem'], $xyzzy['pt_dem']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section patient_particulars -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_2' value='1' data-section="history_proper" checked="checked" />History Proper</td></tr><tr><td><div id="print_history_proper" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Chief Complaints','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['ch_comp'], $xyzzy['ch_comp']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Present History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pr_his'], $xyzzy['pr_his']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Past History','e').':'; ?></td><td class='text data' colspan='3'><?php echo generate_form_field($manual_layouts['past_his'], $xyzzy['past_his']); ?></td><!-- called consumeRows 414--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section history_proper -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_3' value='1' data-section="pers_his" checked="checked" />Personal History</td></tr><tr><td><div id="print_pers_his" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Sleep','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['sleep'], $xyzzy['sleep']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Appetite','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['appetite'], $xyzzy['appetite']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Addiction','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['addiction'], $xyzzy['addiction']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Bowel Habit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['bowel_habit'], $xyzzy['bowel_habit']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Bladder Habit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['bladder_habit'], $xyzzy['bladder_habit']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section pers_his -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_4' value='1' data-section="other_history" checked="checked" />Other History</td></tr><tr><td><div id="print_other_history" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Family History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['fam_his'], $xyzzy['fam_his']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Socioeconomic History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['soc_his'], $xyzzy['soc_his']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Treatment History','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['trt_his'], $xyzzy['trt_his']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section other_history -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_5' value='1' data-section="misc" checked="checked" />Miscellaneous</td></tr><tr><td><div id="print_misc" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Follow Up Needed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['next_visit'], $xyzzy['next_visit']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Appointment Done','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['app_done'], $xyzzy['app_done']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td>
<span class="fieldlabel"><?php xl('Follow up date','e'); ?>: </span>
</td><td>
   <input type='text' size='10' name='follow_date' id='follow_date'
    value="<?php $result=chkdata_Date($xyzzy,'follow_date'); echo $result; ?>"
    />
</td>
<td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Referral Needed','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['ref_need'], $xyzzy['ref_need']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Referred to','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['ref_name'], $xyzzy['ref_name']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Referral Speciality','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['ref_doc'], $xyzzy['ref_doc']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section misc -->
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

