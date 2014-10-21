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
$table_name = 'form_respexam';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'Respiratory Examination';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'respexam';

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
 'chest_shape' => 
   array( 'field_id' => 'chest_shape',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'chest_scar' => 
   array( 'field_id' => 'chest_scar',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'ven_prom' => 
   array( 'field_id' => 'ven_prom',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'sym_mov' => 
   array( 'field_id' => 'sym_mov',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'drop_shoulder' => 
   array( 'field_id' => 'drop_shoulder',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'chest_suck' => 
   array( 'field_id' => 'chest_suck',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'chest_full' => 
   array( 'field_id' => 'chest_full',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'trac_shift' => 
   array( 'field_id' => 'trac_shift',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'apex_beat' => 
   array( 'field_id' => 'apex_beat',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'full_insp' => 
   array( 'field_id' => 'full_insp',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'full_exp' => 
   array( 'field_id' => 'full_exp',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'chest_movement' => 
   array( 'field_id' => 'chest_movement',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'voc_fremitus' => 
   array( 'field_id' => 'voc_fremitus',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_front' => 
   array( 'field_id' => 'per_front',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_axilla' => 
   array( 'field_id' => 'per_axilla',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_back' => 
   array( 'field_id' => 'per_back',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_apex' => 
   array( 'field_id' => 'per_apex',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_clavicle' => 
   array( 'field_id' => 'per_clavicle',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_sternum' => 
   array( 'field_id' => 'per_sternum',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'voc_resonanace' => 
   array( 'field_id' => 'voc_resonanace',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'breath_sound' => 
   array( 'field_id' => 'breath_sound',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'ad_sound' => 
   array( 'field_id' => 'ad_sound',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' )
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
<tr><td class='sectionlabel'>Inspection</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Shape of Chest','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['chest_shape'], $xyzzy['chest_shape']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Any Scars','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['chest_scar'], $xyzzy['chest_scar']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Venous Prominenece','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['ven_prom'], $xyzzy['ven_prom']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Symmetry of Movement','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['sym_mov'], $xyzzy['sym_mov']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Drooping of Shoulder','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['drop_shoulder'], $xyzzy['drop_shoulder']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Intercostal Suction','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['chest_suck'], $xyzzy['chest_suck']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Intercostal Fullness','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['chest_full'], $xyzzy['chest_full']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
<tr><td class='sectionlabel'>Palpation</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Tracheal shifting','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['trac_shift'], $xyzzy['trac_shift']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Position of Apex','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['apex_beat'], $xyzzy['apex_beat']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Measurement at full inspiration','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['full_insp'], $xyzzy['full_insp']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Measurement at full expiration','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['full_exp'], $xyzzy['full_exp']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Chest Movements','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['chest_movement'], $xyzzy['chest_movement']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Vocal fremitus','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['voc_fremitus'], $xyzzy['voc_fremitus']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields)0--></tr>
<tr><td class='sectionlabel'>Percussion</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Front of the chest','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['per_front'], $xyzzy['per_front']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('In Axilla','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['per_axilla'], $xyzzy['per_axilla']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Back Percussion','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['per_back'], $xyzzy['per_back']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Apical Percussion','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['per_apex'], $xyzzy['per_apex']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Clavicle Percussion','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['per_clavicle'], $xyzzy['per_clavicle']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Sternal Percussion','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['per_sternum'], $xyzzy['per_sternum']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields)0--></tr>
<tr><td class='sectionlabel'>Auscultation</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Vocal Resonance','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['voc_resonanace'], $xyzzy['voc_resonanace']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Breath Sounds','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['breath_sound'], $xyzzy['breath_sound']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Adventitious Sound','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['ad_sound'], $xyzzy['ad_sound']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
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

