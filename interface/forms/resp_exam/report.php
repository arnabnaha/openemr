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
function resp_exam_report( $pid, $encounter, $cols, $id) {
    $count = 0;
/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_respexam';


/* an array of all of the fields' names and their types. */
$field_names = array('chest_shape' => 'textfield','chest_scar' => 'dropdown_list','ven_prom' => 'dropdown_list','sym_mov' => 'textfield','drop_shoulder' => 'textfield','chest_suck' => 'textfield','chest_full' => 'textfield','trac_shift' => 'textfield','apex_beat' => 'textfield','full_insp' => 'textfield','full_exp' => 'textfield','chest_movement' => 'textfield','voc_fremitus' => 'textfield','per_front' => 'textfield','per_axilla' => 'textfield','per_back' => 'textfield','per_apex' => 'textfield','per_clavicle' => 'textfield','per_sternum' => 'textfield','voc_resonanace' => 'textfield','breath_sound' => 'textfield','ad_sound' => 'textfield');/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
$manual_layouts = array( 
 'chest_shape' => 
   array( 'field_id' => 'chest_shape',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'chest_scar' => 
   array( 'field_id' => 'chest_scar',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'ven_prom' => 
   array( 'field_id' => 'ven_prom',
          'data_type' => '1',
          'fld_length' => '0',
          'description' => '',
          'list_id' => 'present_absent' ),
 'sym_mov' => 
   array( 'field_id' => 'sym_mov',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'drop_shoulder' => 
   array( 'field_id' => 'drop_shoulder',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'chest_suck' => 
   array( 'field_id' => 'chest_suck',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'chest_full' => 
   array( 'field_id' => 'chest_full',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'trac_shift' => 
   array( 'field_id' => 'trac_shift',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'apex_beat' => 
   array( 'field_id' => 'apex_beat',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'full_insp' => 
   array( 'field_id' => 'full_insp',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'full_exp' => 
   array( 'field_id' => 'full_exp',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'chest_movement' => 
   array( 'field_id' => 'chest_movement',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'voc_fremitus' => 
   array( 'field_id' => 'voc_fremitus',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_front' => 
   array( 'field_id' => 'per_front',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_axilla' => 
   array( 'field_id' => 'per_axilla',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_back' => 
   array( 'field_id' => 'per_back',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_apex' => 
   array( 'field_id' => 'per_apex',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_clavicle' => 
   array( 'field_id' => 'per_clavicle',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'per_sternum' => 
   array( 'field_id' => 'per_sternum',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'voc_resonanace' => 
   array( 'field_id' => 'voc_resonanace',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'breath_sound' => 
   array( 'field_id' => 'breath_sound',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
          'description' => '',
          'list_id' => '' ),
 'ad_sound' => 
   array( 'field_id' => 'ad_sound',
          'data_type' => '2',
          'fld_length' => '30',
          'max_length' => '255',
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
            

            if ($key == 'chest_shape' ) 
            { 
                echo xl_layout_label('Shape of Chest').":";
            }

            if ($key == 'chest_scar' ) 
            { 
                echo xl_layout_label('Any Scars').":";
            }

            if ($key == 'ven_prom' ) 
            { 
                echo xl_layout_label('Venous Prominenece').":";
            }

            if ($key == 'sym_mov' ) 
            { 
                echo xl_layout_label('Symmetry of Movement').":";
            }

            if ($key == 'drop_shoulder' ) 
            { 
                echo xl_layout_label('Drooping of Shoulder').":";
            }

            if ($key == 'chest_suck' ) 
            { 
                echo xl_layout_label('Intercostal Suction').":";
            }

            if ($key == 'chest_full' ) 
            { 
                echo xl_layout_label('Intercostal Fullness').":";
            }

            if ($key == 'trac_shift' ) 
            { 
                echo xl_layout_label('Tracheal shifting').":";
            }

            if ($key == 'apex_beat' ) 
            { 
                echo xl_layout_label('Position of Apex').":";
            }

            if ($key == 'full_insp' ) 
            { 
                echo xl_layout_label('Measurement at full inspiration').":";
            }

            if ($key == 'full_exp' ) 
            { 
                echo xl_layout_label('Measurement at full expiration').":";
            }

            if ($key == 'chest_movement' ) 
            { 
                echo xl_layout_label('Chest Movements').":";
            }

            if ($key == 'voc_fremitus' ) 
            { 
                echo xl_layout_label('Vocal fremitus').":";
            }

            if ($key == 'per_front' ) 
            { 
                echo xl_layout_label('Front of the chest').":";
            }

            if ($key == 'per_axilla' ) 
            { 
                echo xl_layout_label('In Axilla').":";
            }

            if ($key == 'per_back' ) 
            { 
                echo xl_layout_label('Back Percussion').":";
            }

            if ($key == 'per_apex' ) 
            { 
                echo xl_layout_label('Apical Percussion').":";
            }

            if ($key == 'per_clavicle' ) 
            { 
                echo xl_layout_label('Clavicle Percussion').":";
            }

            if ($key == 'per_sternum' ) 
            { 
                echo xl_layout_label('Sternal Percussion').":";
            }

            if ($key == 'voc_resonanace' ) 
            { 
                echo xl_layout_label('Vocal Resonance').":";
            }

            if ($key == 'breath_sound' ) 
            { 
                echo xl_layout_label('Breath Sounds').":";
            }

            if ($key == 'ad_sound' ) 
            { 
                echo xl_layout_label('Adventitious Sound').":";
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

