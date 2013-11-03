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
function gi_exam_report( $pid, $encounter, $cols, $id) {
    $count = 0;
/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_giexam';


/* an array of all of the fields' names and their types. */
$field_names = array('abd_upper' => 'textfield','abd_shape' => 'textfield','abd_flanks' => 'dropdown_list','abd_umbi' => 'textfield','abd_scar' => 'dropdown_list','abd_vein' => 'dropdown_list','abd_movm' => 'textfield','abd_puls' => 'dropdown_list','abd_peris' => 'dropdown_list','abd_lump' => 'textfield','abd_parotid' => 'textfield','abd_spider' => 'textfield','abd_hernia' => 'textfield','abd_hair' => 'textfield','abd_genetalia' => 'textfield','abd_temp' => 'textfield','abd_tender' => 'textfield','abd_vflow' => 'textfield','abd_feel' => 'textfield','abd_loclump' => 'textfield','abd_girth' => 'textfield','abd_oedema' => 'dropdown_list','abd_fthrill' => 'dropdown_list','abd_liver' => 'textarea','abd_gb' => 'textarea','abd_spleen' => 'textarea','abd_kidney' => 'textarea','abd_nodes' => 'textfield','abd_tone' => 'textfield','abd_sdull' => 'textfield','abd_traube' => 'textfield','abd_psound' => 'textfield','abd_splash' => 'dropdown_list','abd_bruit' => 'dropdown_list','abd_spbruit' => 'dropdown_list','abd_cbruit' => 'dropdown_list','abd_auper' => 'textfield','abd_vnhum' => 'textfield','abd_epigast' => 'textfield');/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
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
            

            if ($key == 'abd_upper' ) 
            { 
                echo xl_layout_label('Upper GI').":";
            }

            if ($key == 'abd_shape' ) 
            { 
                echo xl_layout_label('Shape of Abdomen').":";
            }

            if ($key == 'abd_flanks' ) 
            { 
                echo xl_layout_label('Flanks').":";
            }

            if ($key == 'abd_umbi' ) 
            { 
                echo xl_layout_label('Umbilicus').":";
            }

            if ($key == 'abd_scar' ) 
            { 
                echo xl_layout_label('Any Scars').":";
            }

            if ($key == 'abd_vein' ) 
            { 
                echo xl_layout_label('Venous Prominenece').":";
            }

            if ($key == 'abd_movm' ) 
            { 
                echo xl_layout_label('Abdominal Movement').":";
            }

            if ($key == 'abd_puls' ) 
            { 
                echo xl_layout_label('Any Pulsation').":";
            }

            if ($key == 'abd_peris' ) 
            { 
                echo xl_layout_label('Peristalsis').":";
            }

            if ($key == 'abd_lump' ) 
            { 
                echo xl_layout_label('Any obvious lump').":";
            }

            if ($key == 'abd_parotid' ) 
            { 
                echo xl_layout_label('Parotid Swelling').":";
            }

            if ($key == 'abd_spider' ) 
            { 
                echo xl_layout_label('Any Spider naevi').":";
            }

            if ($key == 'abd_hernia' ) 
            { 
                echo xl_layout_label('Hernial sites').":";
            }

            if ($key == 'abd_hair' ) 
            { 
                echo xl_layout_label('Body hairs and Pubic Hair').":";
            }

            if ($key == 'abd_genetalia' ) 
            { 
                echo xl_layout_label('Genetalia').":";
            }

            if ($key == 'abd_temp' ) 
            { 
                echo xl_layout_label('Superficial temperature').":";
            }

            if ($key == 'abd_tender' ) 
            { 
                echo xl_layout_label('Superficial Tenderness').":";
            }

            if ($key == 'abd_vflow' ) 
            { 
                echo xl_layout_label('Venous flow').":";
            }

            if ($key == 'abd_feel' ) 
            { 
                echo xl_layout_label('Feel of Abdomen').":";
            }

            if ($key == 'abd_loclump' ) 
            { 
                echo xl_layout_label('Localised Lump').":";
            }

            if ($key == 'abd_girth' ) 
            { 
                echo xl_layout_label('Abdominal Girth').":";
            }

            if ($key == 'abd_oedema' ) 
            { 
                echo xl_layout_label('Parietal Oedema').":";
            }

            if ($key == 'abd_fthrill' ) 
            { 
                echo xl_layout_label('Fluid Thrill').":";
            }

            if ($key == 'abd_liver' ) 
            { 
                echo xl_layout_label('Liver').":";
            }

            if ($key == 'abd_gb' ) 
            { 
                echo xl_layout_label('Gall Bladder').":";
            }

            if ($key == 'abd_spleen' ) 
            { 
                echo xl_layout_label('Spleen').":";
            }

            if ($key == 'abd_kidney' ) 
            { 
                echo xl_layout_label('kidney').":";
            }

            if ($key == 'abd_nodes' ) 
            { 
                echo xl_layout_label('Pre and Para Aortic Nodes').":";
            }

            if ($key == 'abd_tone' ) 
            { 
                echo xl_layout_label('General Percussion Note').":";
            }

            if ($key == 'abd_sdull' ) 
            { 
                echo xl_layout_label('Sgifting dullness').":";
            }

            if ($key == 'abd_traube' ) 
            { 
                echo xl_layout_label('Traube space percussion').":";
            }

            if ($key == 'abd_psound' ) 
            { 
                echo xl_layout_label('Peristaltic sound').":";
            }

            if ($key == 'abd_splash' ) 
            { 
                echo xl_layout_label('Succussion Splash').":";
            }

            if ($key == 'abd_bruit' ) 
            { 
                echo xl_layout_label('Hepatic bruit').":";
            }

            if ($key == 'abd_spbruit' ) 
            { 
                echo xl_layout_label('Splenic bruit').":";
            }

            if ($key == 'abd_cbruit' ) 
            { 
                echo xl_layout_label('Carotid bruit').":";
            }

            if ($key == 'abd_auper' ) 
            { 
                echo xl_layout_label('Auscultopercussion').":";
            }

            if ($key == 'abd_vnhum' ) 
            { 
                echo xl_layout_label('venous Hum').":";
            }

            if ($key == 'abd_epigast' ) 
            { 
                echo xl_layout_label('Epigastrium Auscultation').":";
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

