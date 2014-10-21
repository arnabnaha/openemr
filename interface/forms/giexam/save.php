<?php
/* this page is intended to be the 'action=' target of a form object.
 * it is called to save the contents of the form into the database
 */

/* for $GLOBALS[], ?? */
require_once('../../globals.php');
/* for acl_check(), ?? */
require_once($GLOBALS['srcdir'].'/api.inc');
/* for ??? */
require_once($GLOBALS['srcdir'].'/forms.inc');
/* for formDataCore() */
require_once($GLOBALS['srcdir'].'/formdata.inc.php');

/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_giexam';

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

/* an array of all of the fields' names and their types. */
$field_names = array('abd_upper' => 'textfield','abd_shape' => 'textfield','abd_flanks' => 'dropdown_list','abd_umbi' => 'textfield','abd_scar' => 'dropdown_list','abd_vein' => 'dropdown_list','abd_movm' => 'textfield','abd_puls' => 'dropdown_list','abd_peris' => 'dropdown_list','abd_lump' => 'textfield','abd_parotid' => 'textfield','abd_spider' => 'textfield','abd_hernia' => 'textfield','abd_hair' => 'textfield','abd_genetalia' => 'textfield','abd_temp' => 'textfield','abd_tender' => 'textfield','abd_vflow' => 'textfield','abd_feel' => 'textfield','abd_loclump' => 'textfield','abd_girth' => 'textfield','abd_oedema' => 'dropdown_list','abd_fthrill' => 'dropdown_list','abd_liver' => 'textarea','abd_gb' => 'textarea','abd_spleen' => 'textarea','abd_kidney' => 'textarea','abd_nodes' => 'textfield','abd_tone' => 'textfield','abd_sdull' => 'textfield','abd_traube' => 'textfield','abd_psound' => 'textfield','abd_splash' => 'dropdown_list','abd_bruit' => 'dropdown_list','abd_spbruit' => 'dropdown_list','abd_cbruit' => 'dropdown_list','abd_auper' => 'textfield','abd_vnhum' => 'textfield','abd_epigast' => 'textfield');
/* an array of the lists the fields may draw on. */
$lists = array('abd_flanks' => 'flank_cond', 'abd_scar' => 'present_absent', 'abd_vein' => 'present_absent', 'abd_puls' => 'present_absent', 'abd_peris' => 'present_absent', 'abd_oedema' => 'present_absent', 'abd_fthrill' => 'present_absent', 'abd_splash' => 'present_absent', 'abd_bruit' => 'present_absent', 'abd_spbruit' => 'present_absent', 'abd_cbruit' => 'present_absent');

/* get each field from $_POST[], storing them into $field_names associated with their names. */
foreach($field_names as $key=>$val)
{
    $pos = '';
    $neg = '';
    if ($val == 'textbox' || $val == 'textarea' || $val == 'provider' || $val == 'textfield')
    {
            $field_names[$key]=$_POST['form_'.$key];
    }
    if ($val == 'date')
    {
        $field_names[$key]=$_POST[$key];
    }
    if (($val == 'checkbox_list' ))
    {
        $field_names[$key]='';
        if (isset($_POST['form_'.$key]) && $_POST['form_'.$key] != 'none' ) /* if the form submitted some entries selected in that field */
        {
            $lres=sqlStatement("select * from list_options where list_id = '".$lists[$key]."' ORDER BY seq, title");
            while ($lrow = sqlFetchArray($lres))
            {
                if (is_array($_POST['form_'.$key]))
                    {
                        if ($_POST['form_'.$key][$lrow[option_id]])
                        {
                            if ($field_names[$key] != '')
                              $field_names[$key]=$field_names[$key].'|';
	                    $field_names[$key] = $field_names[$key].$lrow[option_id];
                        }
                    }
            }
        }
    }
    if (($val == 'checkbox_combo_list'))
    {
        $field_names[$key]='';
        if (isset($_POST['check_'.$key]) && $_POST['check_'.$key] != 'none' ) /* if the form submitted some entries selected in that field */
        {
            $lres=sqlStatement("select * from list_options where list_id = '".$lists[$key]."' ORDER BY seq, title");
            while ($lrow = sqlFetchArray($lres))
            {
                if (is_array($_POST['check_'.$key]))
                {
                    if ($_POST['check_'.$key][$lrow[option_id]])
                    {
                        if ($field_names[$key] != '')
                          $field_names[$key]=$field_names[$key].'|';
                        $field_names[$key] = $field_names[$key].$lrow[option_id].":xx".$_POST['form_'.$key][$lrow[option_id]];
                    }
                }
            }
        }
    }
    if (($val == 'dropdown_list'))
    {
        $field_names[$key]='';
        if (isset($_POST['form_'.$key]) && $_POST['form_'.$key] != 'none' ) /* if the form submitted some entries selected in that field */
        {
            $lres=sqlStatement("select * from list_options where list_id = '".$lists[$key]."' ORDER BY seq, title");
            while ($lrow = sqlFetchArray($lres))
            {
                if ($_POST['form_'.$key] == $lrow[option_id])
                {
                    $field_names[$key]=$lrow[option_id];
                    break;
                }
            }
        }
    }
}

/* at this point, field_names[] contains an array of name->value pairs of the fields we expected from the form. */

/* escape form data for entry to the database. */
foreach ($field_names as $k => $var) {
  $field_names[$k] = formDataCore($var);
}

if ($encounter == '') $encounter = date('Ymd');

if ($_GET['mode'] == 'new') {
    /* NOTE - for customization you can replace $_POST with your own array
     * of key=>value pairs where 'key' is the table field name and
     * 'value' is whatever it should be set to
     * ex)   $newrecord['parent_sig'] = $_POST['sig'];
     *       $newid = formSubmit($table_name, $newrecord, $_GET['id'], $userauthorized);
     */

    /* make sure we're at the beginning of the array */
    reset($field_names);

    /* save the data into the form's encounter-based table */
    $newid = formSubmit($table_name, $field_names, $_GET['id'], $userauthorized);
    /* link this form into the encounter. */
    addForm($encounter, $form_name, $newid, $form_folder, $pid, $userauthorized);
}

elseif ($_GET['mode'] == 'update') {
    /* make sure we're at the beginning of the array */
    reset($field_names);

    /* update the data in the form's table */
    $success = formUpdate($table_name, $field_names, $_GET['id'], $userauthorized);
    /* sqlInsert('update '.$table_name." set pid = {".$_SESSION['pid']."},groupname='".$_SESSION['authProvider']."',user='".$_SESSION['authUser']."',authorized=$userauthorized,activity=1,date = NOW(), where id=$id"); */
}


$_SESSION['encounter'] = $encounter;

formHeader('Redirecting....');
/* defaults to the encounters page. */
formJump();

formFooter();
?>

