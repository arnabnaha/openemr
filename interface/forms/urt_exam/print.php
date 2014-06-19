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
$table_name = 'form_urt';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'Upper Respiratory Tract Examination';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'urt_exam';

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
 'inspect_gen' => 
   array( 'field_id' => 'inspect_gen',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'inspect_lip' => 
   array( 'field_id' => 'inspect_lip',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'inspect_teeth' => 
   array( 'field_id' => 'inspect_teeth',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'inspect_tongue' => 
   array( 'field_id' => 'inspect_tongue',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'inspect_oral' => 
   array( 'field_id' => 'inspect_oral',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'inspect_posterior' => 
   array( 'field_id' => 'inspect_posterior',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'inspect_tonsil' => 
   array( 'field_id' => 'inspect_tonsil',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'inspect_gum' => 
   array( 'field_id' => 'inspect_gum',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'inspect_vestibule' => 
   array( 'field_id' => 'inspect_vestibule',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'inspect_uvula' => 
   array( 'field_id' => 'inspect_uvula',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'palp_glands' => 
   array( 'field_id' => 'palp_glands',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'palp_neck' => 
   array( 'field_id' => 'palp_neck',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'palp_extra' => 
   array( 'field_id' => 'palp_extra',
          'data_type' => '3',
          'fld_length' => '10',
          'max_length' => '3',
          'description' => '',
          'list_id' => '' )
 );

$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';


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
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_1' value='1' data-section="inspection" checked="checked" />Inspection</td></tr><tr><td><div id="print_inspection" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('General Look','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['inspect_gen'], $xyzzy['inspect_gen']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Lip','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['inspect_lip'], $xyzzy['inspect_lip']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Teeth','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['inspect_teeth'], $xyzzy['inspect_teeth']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Tongue','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['inspect_tongue'], $xyzzy['inspect_tongue']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Oral Cavity','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['inspect_oral'], $xyzzy['inspect_oral']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Posterior Pharynx','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['inspect_posterior'], $xyzzy['inspect_posterior']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Tonsil','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['inspect_tonsil'], $xyzzy['inspect_tonsil']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Gingiva','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['inspect_gum'], $xyzzy['inspect_gum']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Vestibule','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['inspect_vestibule'], $xyzzy['inspect_vestibule']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Uvula','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['inspect_uvula'], $xyzzy['inspect_uvula']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section inspection -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_2' value='1' data-section="palpation" checked="checked" />Palpation</td></tr><tr><td><div id="print_palpation" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Salivary Glands','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['palp_glands'], $xyzzy['palp_glands']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Neck Glands','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['palp_neck'], $xyzzy['palp_neck']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Extra Examination Finding','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['palp_extra'], $xyzzy['palp_extra']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section palpation -->
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

