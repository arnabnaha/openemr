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
function urt_report( $pid, $encounter, $cols, $id) {
    $count = 0;
/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_urt';


/* an array of all of the fields' names and their types. */
$field_names = array('inspect_gen' => 'textfield','inspect_lip' => 'textfield','inspect_teeth' => 'textfield','inspect_tongue' => 'textfield','inspect_oral' => 'textfield','inspect_posterior' => 'textfield','inspect_tonsil' => 'textfield','inspect_gum' => 'textfield','inspect_vestibule' => 'textfield','inspect_uvula' => 'textfield','palp_glands' => 'textfield','palp_neck' => 'textfield','palp_extra' => 'textarea');/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
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
            

            if ($key == 'inspect_gen' ) 
            { 
                echo xl_layout_label('General Look').":";
            }

            if ($key == 'inspect_lip' ) 
            { 
                echo xl_layout_label('Lip').":";
            }

            if ($key == 'inspect_teeth' ) 
            { 
                echo xl_layout_label('Teeth').":";
            }

            if ($key == 'inspect_tongue' ) 
            { 
                echo xl_layout_label('Tongue').":";
            }

            if ($key == 'inspect_oral' ) 
            { 
                echo xl_layout_label('Oral Cavity').":";
            }

            if ($key == 'inspect_posterior' ) 
            { 
                echo xl_layout_label('Posterior Pharynx').":";
            }

            if ($key == 'inspect_tonsil' ) 
            { 
                echo xl_layout_label('Tonsil').":";
            }

            if ($key == 'inspect_gum' ) 
            { 
                echo xl_layout_label('Gingiva').":";
            }

            if ($key == 'inspect_vestibule' ) 
            { 
                echo xl_layout_label('Vestibule').":";
            }

            if ($key == 'inspect_uvula' ) 
            { 
                echo xl_layout_label('Uvula').":";
            }

            if ($key == 'palp_glands' ) 
            { 
                echo xl_layout_label('Salivary Glands').":";
            }

            if ($key == 'palp_neck' ) 
            { 
                echo xl_layout_label('Neck Glands').":";
            }

            if ($key == 'palp_extra' ) 
            { 
                echo xl_layout_label('Extra Examination Finding').":";
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

