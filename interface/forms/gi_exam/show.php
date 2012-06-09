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
$table_name = 'form_giexam';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'GI Examination';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'gi_exam';

/* Check the access control lists to ensure permissions to this page */
$thisauth = acl_check('patients', 'med');
if (!$thisauth) {
 die($form_name.': Access Denied.');
}
/* perform a squad check for pages touching patients, if we're in 'athletic team' mode */
if ($GLOBALS['athletic_team']!='false') {
  $tmp = getPatientData($pid, 'squad');
  if ($tmp['squad'] && ! acl_check('squads', $tmp['squad']))
   $thisauth = 0;
}
/* Use the formFetch function from api.inc to load the saved record */
$xyzzy = formFetch($table_name, $_GET['id']);

/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
$manual_layouts = array( 
 'abd_upper' => 
   array( 'field_id' => 'abd_upper',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_shape' => 
   array( 'field_id' => 'abd_shape',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_flanks' => 
   array( 'field_id' => 'abd_flanks',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'flank_cond' ),
 'abd_umbi' => 
   array( 'field_id' => 'abd_umbi',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_scar' => 
   array( 'field_id' => 'abd_scar',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'abd_vein' => 
   array( 'field_id' => 'abd_vein',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'abd_movm' => 
   array( 'field_id' => 'abd_movm',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_puls' => 
   array( 'field_id' => 'abd_puls',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'abd_peris' => 
   array( 'field_id' => 'abd_peris',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'abd_lump' => 
   array( 'field_id' => 'abd_lump',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_parotid' => 
   array( 'field_id' => 'abd_parotid',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_spider' => 
   array( 'field_id' => 'abd_spider',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_hernia' => 
   array( 'field_id' => 'abd_hernia',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_hair' => 
   array( 'field_id' => 'abd_hair',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_genetalia' => 
   array( 'field_id' => 'abd_genetalia',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_temp' => 
   array( 'field_id' => 'abd_temp',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_tender' => 
   array( 'field_id' => 'abd_tender',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_vflow' => 
   array( 'field_id' => 'abd_vflow',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_feel' => 
   array( 'field_id' => 'abd_feel',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_loclump' => 
   array( 'field_id' => 'abd_loclump',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_girth' => 
   array( 'field_id' => 'abd_girth',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_oedema' => 
   array( 'field_id' => 'abd_oedema',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'abd_fthrill' => 
   array( 'field_id' => 'abd_fthrill',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'abd_liver' => 
   array( 'field_id' => 'abd_liver',
          'data_type' => '3',
          'fld_length' => '75',
          'max_length' => '4',
          'description' => '',
          'list_id' => '' ),
 'abd_gb' => 
   array( 'field_id' => 'abd_gb',
          'data_type' => '3',
          'fld_length' => '75',
          'max_length' => '4',
          'description' => '',
          'list_id' => '' ),
 'abd_spleen' => 
   array( 'field_id' => 'abd_spleen',
          'data_type' => '3',
          'fld_length' => '75',
          'max_length' => '4',
          'description' => '',
          'list_id' => '' ),
 'abd_kidney' => 
   array( 'field_id' => 'abd_kidney',
          'data_type' => '3',
          'fld_length' => '75',
          'max_length' => '4',
          'description' => '',
          'list_id' => '' ),
 'abd_nodes' => 
   array( 'field_id' => 'abd_nodes',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_tone' => 
   array( 'field_id' => 'abd_tone',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_sdull' => 
   array( 'field_id' => 'abd_sdull',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_traube' => 
   array( 'field_id' => 'abd_traube',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_psound' => 
   array( 'field_id' => 'abd_psound',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_splash' => 
   array( 'field_id' => 'abd_splash',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'abd_bruit' => 
   array( 'field_id' => 'abd_bruit',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'abd_spbruit' => 
   array( 'field_id' => 'abd_spbruit',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'abd_cbruit' => 
   array( 'field_id' => 'abd_cbruit',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'abd_auper' => 
   array( 'field_id' => 'abd_auper',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_vnhum' => 
   array( 'field_id' => 'abd_vnhum',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'abd_epigast' => 
   array( 'field_id' => 'abd_epigast',
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
 if ($thisauth == 'write' || $thisauth == 'addonly')
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
<tr><td class='sectionlabel'>Inspection</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Upper GI','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_upper'], $xyzzy['abd_upper']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Shape of Abdomen','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_shape'], $xyzzy['abd_shape']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Flanks','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_flanks'], $xyzzy['abd_flanks']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Umbilicus','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_umbi'], $xyzzy['abd_umbi']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Any Scars','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_scar'], $xyzzy['abd_scar']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Venous Prominenece','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_vein'], $xyzzy['abd_vein']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Abdominal Movement','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_movm'], $xyzzy['abd_movm']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Any Pulsation','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_puls'], $xyzzy['abd_puls']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Peristalsis','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_peris'], $xyzzy['abd_peris']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Any obvious lump','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_lump'], $xyzzy['abd_lump']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Parotid Swelling','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_parotid'], $xyzzy['abd_parotid']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Any Spider naevi','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_spider'], $xyzzy['abd_spider']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Hernial sites','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_hernia'], $xyzzy['abd_hernia']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Body hairs and Pubic Hair','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_hair'], $xyzzy['abd_hair']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('UGenetalia','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_genetalia'], $xyzzy['abd_genetalia']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
<tr><td class='sectionlabel'>Palpation</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Superficial temperature','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_temp'], $xyzzy['abd_temp']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Superficial Tenderness','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_tender'], $xyzzy['abd_tender']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Venous flow','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_vflow'], $xyzzy['abd_vflow']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Feel of Abdomen','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_feel'], $xyzzy['abd_feel']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Localised Lump','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_loclump'], $xyzzy['abd_loclump']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Abdominal Girth','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_girth'], $xyzzy['abd_girth']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Parietal Oedema','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_oedema'], $xyzzy['abd_oedema']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Fluid Thrill','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_fthrill'], $xyzzy['abd_fthrill']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Liver','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_liver'], $xyzzy['abd_liver']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Gall Bladder','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_gb'], $xyzzy['abd_gb']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Spleen','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_spleen'], $xyzzy['abd_spleen']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('kidney','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_kidney'], $xyzzy['abd_kidney']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Pre and Para Aortic Nodes','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_nodes'], $xyzzy['abd_nodes']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
<tr><td class='sectionlabel'>Percussion</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('General Percussion Note','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_tone'], $xyzzy['abd_tone']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Sgifting dullness','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_sdull'], $xyzzy['abd_sdull']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Traube space percussion','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_traube'], $xyzzy['abd_traube']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields)2--><td class='emptycell' colspan='1'></td></tr>
<tr><td class='sectionlabel'>Auscultation</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Peristaltic sound','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_psound'], $xyzzy['abd_psound']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Succussion Splash','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_splash'], $xyzzy['abd_splash']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Hepatic bruit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_bruit'], $xyzzy['abd_bruit']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Splenic bruit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_spbruit'], $xyzzy['abd_spbruit']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Carotid bruit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_cbruit'], $xyzzy['abd_cbruit']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Auscultopercussion','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_auper'], $xyzzy['abd_auper']); ?></td></tr>
<tr><td valign='top'>&nbsp;</td><!-- called consumeRows 014--> <!-- called consumeRows 224--> <td class='fieldlabel' colspan='1'><?php echo xl_layout_label('venous Hum','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_vnhum'], $xyzzy['abd_vnhum']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Epigastrium Auscultation','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_display_field($manual_layouts['abd_epigast'], $xyzzy['abd_epigast']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields)0--></tr>
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

