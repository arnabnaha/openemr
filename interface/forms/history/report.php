<?php
/*
 * this file's contents are included in both the encounter page as a 'quick summary' of a form, and in the medical records' reports page.
 */

/* for $GLOBALS[], ?? */
require_once('../../globals.php');
/* for acl_check(), ?? */
require_once($GLOBALS['srcdir'].'/api.inc');
/* for generate_display_field() */
require_once($GLOBALS['srcdir'].'/options.inc.php');
/* The name of the function is significant and must match the folder name */
function history_report( $pid, $encounter, $cols, $id) {
    $count = 0;
/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_history';


/* an array of all of the fields' names and their types. */
$field_names = array('pt_name' => 'textfield','date_visit' => 'date','pt_age' => 'textfield','pt_respo' => 'dropdown_list','pt_rel' => 'dropdown_list','pt_dem' => 'dropdown_list','ch_comp' => 'textarea','pr_his' => 'textarea','past_his' => 'textarea','sleep' => 'textfield','appetite' => 'textfield','addiction' => 'dropdown_list','bowel_habit' => 'textfield','bladder_habit' => 'textfield','fam_his' => 'checkbox_list','soc_his' => 'checkbox_list','trt_his' => 'textarea','next_visit' => 'dropdown_list','app_done' => 'dropdown_list','follow_date' => 'date');/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
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
/* an array of the lists the fields may draw on. */
$lists = array();
    $data = formFetch($table_name, $id);
    if ($data) {

        echo '<table><tr>';

        foreach($data as $key => $value) {

            if ($key == 'id' || $key == 'pid' || $key == 'user' ||
                $key == 'groupname' || $key == 'authorized' ||
                $key == 'activity' || $key == 'date' || 
                $value == '' || $value == '0000-00-00 00:00:00' ||
                $value == 'n')
            {
                /* skip built-in fields and "blank data". */
	        continue;
            }

            /* display 'yes' instead of 'on'. */
            if ($value == 'on') {
                $value = 'yes';
            }

            /* remove the time-of-day from the 'date' fields. */
            if ($field_names[$key] == 'date')
            if ($value != '') {
              $dateparts = split(' ', $value);
              $value = $dateparts[0];
            }

	    echo "<td><span class='bold'>";
            

            if ($key == 'pt_name' ) 
            { 
                echo xl_layout_label('Patient Name').":";
            }

            if ($key == 'date_visit' ) 
            { 
                echo xl_layout_label('Date of Visit').":";
            }

            if ($key == 'pt_age' ) 
            { 
                echo xl_layout_label('Age').":";
            }

            if ($key == 'pt_respo' ) 
            { 
                echo xl_layout_label('Respondent').":";
            }

            if ($key == 'pt_rel' ) 
            { 
                echo xl_layout_label('Relation to Patient').":";
            }

            if ($key == 'pt_dem' ) 
            { 
                echo xl_layout_label('Demographics Complete').":";
            }

            if ($key == 'ch_comp' ) 
            { 
                echo xl_layout_label('Chief Complaints').":";
            }

            if ($key == 'pr_his' ) 
            { 
                echo xl_layout_label('Present History').":";
            }

            if ($key == 'past_his' ) 
            { 
                echo xl_layout_label('Past History').":";
            }

            if ($key == 'sleep' ) 
            { 
                echo xl_layout_label('Sleep').":";
            }

            if ($key == 'appetite' ) 
            { 
                echo xl_layout_label('Appetite').":";
            }

            if ($key == 'addiction' ) 
            { 
                echo xl_layout_label('Addiction').":";
            }

            if ($key == 'bowel_habit' ) 
            { 
                echo xl_layout_label('Bowel Habit').":";
            }

            if ($key == 'bladder_habit' ) 
            { 
                echo xl_layout_label('Bladder Habit').":";
            }

            if ($key == 'fam_his' ) 
            { 
                echo xl_layout_label('Family History').":";
            }

            if ($key == 'soc_his' ) 
            { 
                echo xl_layout_label('Socioeconomic History').":";
            }

            if ($key == 'trt_his' ) 
            { 
                echo xl_layout_label('Treatment History').":";
            }

            if ($key == 'next_visit' ) 
            { 
                echo xl_layout_label('Follow Up Needed').":";
            }

            if ($key == 'app_done' ) 
            { 
                echo xl_layout_label('Appointment Done').":";
            }

            if ($key == 'follow_date' ) 
            { 
                echo xl_layout_label('Follow up date').":";
            }

                echo '</span><span class=text>'.generate_display_field( $manual_layouts[$key], $value ).'</span></td>';

            $count++;
            if ($count == $cols) {
                $count = 0;
                echo '</tr><tr>' . PHP_EOL;
            }
        }
    }
    echo '</tr></table><hr>';
}
?>

