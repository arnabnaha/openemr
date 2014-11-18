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
function followup_report( $pid, $encounter, $cols, $id) {
    $count = 0;
/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_followup';


/* an array of all of the fields' names and their types. */
$field_names = array('last_enc' => 'date','date_visit' => 'date','enc_number' => 'textfield','reason_follow' => 'checkbox_list','diag_last' => 'textfield','follow_note' => 'textarea','present_state' => 'dropdown_list','diag_change' => 'dropdown_list','new_diag' => 'textfield','trt_change' => 'dropdown_list','new_inv' => 'dropdown_list','next_visit' => 'dropdown_list','app_done' => 'dropdown_list','app_date' => 'date');/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
$manual_layouts = array( 
 'last_enc' => 
   array( 'field_id' => 'last_enc',
          'data_type' => '4',
          'fld_length' => '0',
          'description' => '',
          'list_id' => '' ),
 'date_visit' => 
   array( 'field_id' => 'date_visit',
          'data_type' => '4',
          'fld_length' => '0',
          'description' => '',
          'list_id' => '' ),
 'enc_number' => 
   array( 'field_id' => 'enc_number',
          'data_type' => '2',
          'fld_length' => '20',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'reason_follow' => 
   array( 'field_id' => 'reason_follow',
          'data_type' => '21',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'reas_follow' ),
 'diag_last' => 
   array( 'field_id' => 'diag_last',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'follow_note' => 
   array( 'field_id' => 'follow_note',
          'data_type' => '3',
          'fld_length' => '50',
          'max_length' => '4',
          'description' => '',
          'list_id' => '' ),
 'present_state' => 
   array( 'field_id' => 'present_state',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'outcome' ),
 'diag_change' => 
   array( 'field_id' => 'diag_change',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
 'new_diag' => 
   array( 'field_id' => 'new_diag',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'trt_change' => 
   array( 'field_id' => 'trt_change',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
 'new_inv' => 
   array( 'field_id' => 'new_inv',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' ),
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
 'app_date' => 
   array( 'field_id' => 'app_date',
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
            

            if ($key == 'last_enc' ) 
            { 
                echo xl_layout_label('Date of Last Encounter').":";
            }

            if ($key == 'date_visit' ) 
            { 
                echo xl_layout_label('Date of Follow up').":";
            }

            if ($key == 'enc_number' ) 
            { 
                echo xl_layout_label('Last Encounter Number').":";
            }

            if ($key == 'reason_follow' ) 
            { 
                echo xl_layout_label('Reason for follow up').":";
            }

            if ($key == 'diag_last' ) 
            { 
                echo xl_layout_label('Diagnosis on last encounter').":";
            }

            if ($key == 'follow_note' ) 
            { 
                echo xl_layout_label('Follow up Note').":";
            }

            if ($key == 'present_state' ) 
            { 
                echo xl_layout_label('Present Status').":";
            }

            if ($key == 'diag_change' ) 
            { 
                echo xl_layout_label('Diagnosis Changed').":";
            }

            if ($key == 'new_diag' ) 
            { 
                echo xl_layout_label('New Diagnosis').":";
            }

            if ($key == 'trt_change' ) 
            { 
                echo xl_layout_label('Treatment Changed').":";
            }

            if ($key == 'new_inv' ) 
            { 
                echo xl_layout_label('New Investigations').":";
            }

            if ($key == 'next_visit' ) 
            { 
                echo xl_layout_label('Next Visit Needed').":";
            }

            if ($key == 'app_done' ) 
            { 
                echo xl_layout_label('Appointment Done').":";
            }

            if ($key == 'app_date' ) 
            { 
                echo xl_layout_label('Next Visit Date').":";
            }

                echo '<br></span><span class=text>'.generate_display_field( $manual_layouts[$key], wordwrap($value, 35, "\n", true) ).'</span></td>';

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

