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
$table_name = 'form_urt';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'Upper Respiratory Tract Examination';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'urt';

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
<tr><td class='sectionlabel'>Inspection</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('General Look','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['inspect_gen'], $xyzzy['inspect_gen']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Lip','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['inspect_lip'], $xyzzy['inspect_lip']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Teeth','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['inspect_teeth'], $xyzzy['inspect_teeth']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Tongue','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['inspect_tongue'], $xyzzy['inspect_tongue']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Oral Cavity','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['inspect_oral'], $xyzzy['inspect_oral']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Posterior Pharynx','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['inspect_posterior'], $xyzzy['inspect_posterior']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Tonsil','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['inspect_tonsil'], $xyzzy['inspect_tonsil']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Gingiva','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['inspect_gum'], $xyzzy['inspect_gum']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Vestibule','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['inspect_vestibule'], $xyzzy['inspect_vestibule']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Uvula','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['inspect_uvula'], $xyzzy['inspect_uvula']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields)0--></tr>
<tr><td class='sectionlabel'>Palpation</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Salivary Glands','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['palp_glands'], $xyzzy['palp_glands']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Neck Glands','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['palp_neck'], $xyzzy['palp_neck']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Extra Examination Finding','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['palp_extra'], $xyzzy['palp_extra']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
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

