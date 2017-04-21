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
$table_name = 'form_gensurvey';

/** CHANGE THIS name to the name of your form. **/
$form_name = 'General Survey';

/** CHANGE THIS to match the folder you created for this form. **/
$form_folder = 'gensurvey';

/* Check the access control lists to ensure permissions to this page */
if (!acl_check('patients', 'med')) {
 die(text($form_name).': '.xlt("Access Denied"));
}
$thisauth_write_addonly=FALSE;
if ( acl_check('patients','med','',array('write','addonly') )) {
 $thisauth_write_addonly=TRUE;
}

if (!$thisauth_write_addonly)
  die(text($form_name).': '.xlt("Adding is not authorized"));
/* Use the formFetch function from api.inc to load the saved record */
$xyzzy = formFetch($table_name, $_GET['id']);

/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
$manual_layouts = array( 
 'pts_name' => 
   array( 'field_id' => 'pts_name',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'gens_date' => 
   array( 'field_id' => 'gens_date',
          'data_type' => '4',
          'fld_length' => '0',
          'description' => '',
          'list_id' => '' ),
 'genu_age' => 
   array( 'field_id' => 'genu_age',
          'data_type' => '2',
          'fld_length' => '10',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'geny_examined' => 
   array( 'field_id' => 'geny_examined',
          'data_type' => '10',
          'fld_length' => '0',
          'description' => '',
          'list_id' => '' ),
 'genr_higher' => 
   array( 'field_id' => 'genr_higher',
          'data_type' => '2',
          'fld_length' => '10',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'fc_facies' => 
   array( 'field_id' => 'fc_facies',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'bu_build' => 
   array( 'field_id' => 'bu_build',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'build_list' ),
 'nu_nutri' => 
   array( 'field_id' => 'nu_nutri',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'decu_gen' => 
   array( 'field_id' => 'decu_gen',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'decubitus_list' ),
 'sur_pal' => 
   array( 'field_id' => 'sur_pal',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'cya_cyanosis' => 
   array( 'field_id' => 'cya_cyanosis',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'cl_clubbing' => 
   array( 'field_id' => 'cl_clubbing',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'gr_clubgrade' => 
   array( 'field_id' => 'gr_clubgrade',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'clubbing_grades' ),
 'ic_icterus' => 
   array( 'field_id' => 'ic_icterus',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'oe_oedema' => 
   array( 'field_id' => 'oe_oedema',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'loc_locoedema' => 
   array( 'field_id' => 'loc_locoedema',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'oedema_location' ),
 'vit_vitals' => 
   array( 'field_id' => 'vit_vitals',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
 'no_node' => 
   array( 'field_id' => 'no_node',
          'data_type' => '2',
          'fld_length' => '10',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'ne_vein' => 
   array( 'field_id' => 'ne_vein',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'sk_skin' => 
   array( 'field_id' => 'sk_skin',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'sku_skull' => 
   array( 'field_id' => 'sku_skull',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'ex_extra' => 
   array( 'field_id' => 'ex_extra',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'imp_impression' => 
   array( 'field_id' => 'imp_impression',
          'data_type' => '2',
          'fld_length' => '50',
          'max_length' => '255',
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
 $returnurl = 'encounter_top.php';
}

/* remove the time-of-day from all date fields */
if ($xyzzy['gens_date'] != '') {
    $dateparts = split(' ', $xyzzy['gens_date']);
    $xyzzy['gens_date'] = $dateparts[0];
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
<!-- For jquery, required by the save, discard, and print buttons. -->
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-min-3-1-1/index.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/textformat.js?v=<?php echo $v_js_includes; ?>"></script>

<!-- Global Stylesheet -->
<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css"/>
<!-- Form Specific Stylesheet. -->
<link rel="stylesheet" href="../../forms/<?php echo $form_folder; ?>/style.css" type="text/css"/>

<!-- supporting code for the pop up calendar(date picker) -->
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-datetimepicker-2-5-4/build/jquery.datetimepicker.min.css">
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-datetimepicker-2-5-4/build/jquery.datetimepicker.full.min.js"></script>


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
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_1' value='1' data-section="patient_particulars" checked="checked" />Patient Particulars</td></tr><tr><td><div id="patient_particulars" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Patient Name','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['pts_name'], $xyzzy['pts_name']); ?></td><td>
<span class="fieldlabel"><?php xl('Date of Visit','e'); ?> (yyyy-mm-dd): </span>
</td><td>
   <input type='text' size='10' class='datepicker' name='gens_date' id='gens_date'
    value="<?php $result=chkdata_Date($xyzzy,'gens_date'); echo $result; ?>"
</td>
<!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Age','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['genu_age'], $xyzzy['genu_age']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Examined By','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['geny_examined'], $xyzzy['geny_examined']); ?></td><!-- called consumeRows 424--> <!-- Exiting not($fields) and generating 0 empty fields --></tr>
</table></div>
</td></tr> <!-- end section patient_particulars -->
<tr><td class='sectionlabel'><input type='checkbox' id='form_cb_m_2' value='1' data-section="gen_survey" checked="checked" />General Survey</td></tr><tr><td><div id="gen_survey" class='section'><table>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Higher Functions','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['genr_higher'], $xyzzy['genr_higher']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Facies','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['fc_facies'], $xyzzy['fc_facies']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Build','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['bu_build'], $xyzzy['bu_build']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Nutrition','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['nu_nutri'], $xyzzy['nu_nutri']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Decubitus','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['decu_gen'], $xyzzy['decu_gen']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Pallor','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['sur_pal'], $xyzzy['sur_pal']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Cyanosis','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['cya_cyanosis'], $xyzzy['cya_cyanosis']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Clubbing','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['cl_clubbing'], $xyzzy['cl_clubbing']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Grade of Clubbing','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['gr_clubgrade'], $xyzzy['gr_clubgrade']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Icterus','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['ic_icterus'], $xyzzy['ic_icterus']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Oedema','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['oe_oedema'], $xyzzy['oe_oedema']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Oedema LOcation','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['loc_locoedema'], $xyzzy['loc_locoedema']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Vitals Recorded','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['vit_vitals'], $xyzzy['vit_vitals']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Lymph Nodes','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['no_node'], $xyzzy['no_node']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Neck Vein','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['ne_vein'], $xyzzy['ne_vein']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Skin and Appendages','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['sk_skin'], $xyzzy['sk_skin']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- just calling --><!-- called consumeRows 224--> <!--  generating 4 cells and calling --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Cranium and Spine','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['sku_skull'], $xyzzy['sku_skull']); ?></td><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Extra Points','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['ex_extra'], $xyzzy['ex_extra']); ?></td><!--  generating empties --><td class='emptycell' colspan='1'></td></tr>
<!-- called consumeRows 014--> <!-- generating not($fields[$checked+1]) and calling last --><td class='fieldlabel' colspan='1'><?php echo xl_layout_label('Impression','e').':'; ?></td><td class='text data' colspan='1'><?php echo generate_form_field($manual_layouts['imp_impression'], $xyzzy['imp_impression']); ?></td><!-- called consumeRows 214--> <!-- Exiting not($fields) and generating 2 empty fields --><td class='emptycell' colspan='1'></td></tr>
</table></div>
</td></tr> <!-- end section gen_survey -->
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

