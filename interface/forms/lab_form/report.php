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
function lab_form_report( $pid, $encounter, $cols, $id) {
    $count = 0;
/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_labs';


/* an array of all of the fields' names and their types. */
$field_names = array('blood_one' => 'dropdown_list','blood_two' => 'dropdown_list','blood_three' => 'dropdown_list','blood_four' => 'dropdown_list','blood_five' => 'dropdown_list','radio_one' => 'dropdown_list','radio_two' => 'dropdown_list','radio_three' => 'dropdown_list','radio_four' => 'dropdown_list','radio_five' => 'dropdown_list','date_report' => 'textfield','report_upload' => 'dropdown_list');/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
$manual_layouts = array( 
 'blood_one' => 
   array( 'field_id' => 'blood_one',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Blood_Investigations' ),
 'blood_two' => 
   array( 'field_id' => 'blood_two',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Blood_Investigations' ),
 'blood_three' => 
   array( 'field_id' => 'blood_three',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Blood_Investigations' ),
 'blood_four' => 
   array( 'field_id' => 'blood_four',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Blood_Investigations' ),
 'blood_five' => 
   array( 'field_id' => 'blood_five',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Blood_Investigations' ),
 'radio_one' => 
   array( 'field_id' => 'radio_one',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Radiology_Investigations' ),
 'radio_two' => 
   array( 'field_id' => 'radio_two',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Radiology_Investigations' ),
 'radio_three' => 
   array( 'field_id' => 'radio_three',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Radiology_Investigations' ),
 'radio_four' => 
   array( 'field_id' => 'radio_four',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Radiology_Investigations' ),
 'radio_five' => 
   array( 'field_id' => 'radio_five',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'Radiology_Investigations' ),
 'date_report' => 
   array( 'field_id' => 'date_report',
          'data_type' => '2',
          'fld_length' => '20',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'report_upload' => 
   array( 'field_id' => 'report_upload',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'yesno' )
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
            

            if ($key == 'blood_one' ) 
            { 
                echo xl_layout_label('1.').":";
            }

            if ($key == 'blood_two' ) 
            { 
                echo xl_layout_label('2.').":";
            }

            if ($key == 'blood_three' ) 
            { 
                echo xl_layout_label('3.').":";
            }

            if ($key == 'blood_four' ) 
            { 
                echo xl_layout_label('4.').":";
            }

            if ($key == 'blood_five' ) 
            { 
                echo xl_layout_label('5.').":";
            }

            if ($key == 'radio_one' ) 
            { 
                echo xl_layout_label('1.').":";
            }

            if ($key == 'radio_two' ) 
            { 
                echo xl_layout_label('2.').":";
            }

            if ($key == 'radio_three' ) 
            { 
                echo xl_layout_label('3.').":";
            }

            if ($key == 'radio_four' ) 
            { 
                echo xl_layout_label('4.').":";
            }

            if ($key == 'radio_five' ) 
            { 
                echo xl_layout_label('5.').":";
            }

            if ($key == 'date_report' ) 
            { 
                echo xl_layout_label('Date of Report').":";
            }

            if ($key == 'report_upload' ) 
            { 
                echo xl_layout_label('Reports Uploaded').":";
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

