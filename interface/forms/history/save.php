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

/* an array of all of the fields' names and their types. */
$field_names = array('pt_name' => 'textfield','date_visit' => 'date','pt_age' => 'textfield','pt_respo' => 'dropdown_list','pt_rel' => 'dropdown_list','pt_dem' => 'dropdown_list','ch_comp' => 'textarea','pr_his' => 'textarea','past_his' => 'textarea','sleep' => 'textfield','appetite' => 'textfield','addiction' => 'dropdown_list','bowel_habit' => 'textfield','bladder_habit' => 'textfield','fam_his' => 'checkbox_list','soc_his' => 'checkbox_list','trt_his' => 'textarea','next_visit' => 'dropdown_list','app_done' => 'dropdown_list','follow_date' => 'date','ref_need' => 'dropdown_list','ref_name' => 'textfield','ref_doc' => 'dropdown_list');
/* an array of the lists the fields may draw on. */
$lists = array('pt_respo' => 'Respondent', 'pt_rel' => 'Relationship_list', 'pt_dem' => 'yesno', 'addiction' => 'addiction_status', 'fam_his' => 'hist_take', 'soc_his' => 'hist_take', 'next_visit' => 'yesno', 'app_done' => 'yesno', 'ref_need' => 'yesno', 'ref_doc' => 'Referring_Speciality');

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

