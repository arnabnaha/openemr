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
$form_name = 'GI Examination';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'giexam';

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
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_1' value='1' data-section="inspection" checked="checked" />Inspection</td></tr><tr><td><div id="inspection" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Upper GI','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_upper'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Shape of Abdomen','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_shape'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Flanks','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_flanks'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Umbilicus','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_umbi'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Any Scars','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_scar'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Venous Prominenece','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_vein'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Abdominal Movement','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_movm'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Any Pulsation','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_puls'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Peristalsis','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_peris'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Any obvious lump','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_lump'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Parotid Swelling','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_parotid'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Any Spider naevi','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_spider'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Hernial sites','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_hernia'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Body hairs and Pubic Hair','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_hair'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Genetalia','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_genetalia'], ''); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section inspection -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_2' value='1' data-section="palpation" checked="checked" />Palpation</td></tr><tr><td><div id="palpation" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Superficial temperature','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_temp'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Superficial Tenderness','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_tender'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Venous flow','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_vflow'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Feel of Abdomen','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_feel'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Localised Lump','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_loclump'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Abdominal Girth','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_girth'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Parietal Oedema','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_oedema'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Fluid Thrill','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_fthrill'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Liver','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_liver'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Gall Bladder','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_gb'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Spleen','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_spleen'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('kidney','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_kidney'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Pre and Para Aortic Nodes','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_nodes'], ''); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section palpation -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_3' value='1' data-section="percussion" checked="checked" />Percussion</td></tr><tr><td><div id="percussion" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('General Percussion Note','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_tone'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Sgifting dullness','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_sdull'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Traube space percussion','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_traube'], ''); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section percussion -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_4' value='1' data-section="auscultation" checked="checked" />Auscultation</td></tr><tr><td><div id="auscultation" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Peristaltic sound','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_psound'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Succussion Splash','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_splash'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Hepatic bruit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_bruit'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Splenic bruit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_spbruit'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Carotid bruit','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_cbruit'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Auscultopercussion','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_auper'], ''); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('venous Hum','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_vnhum'], ''); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Epigastrium Auscultation','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['abd_epigast'], ''); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section auscultation -->
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

