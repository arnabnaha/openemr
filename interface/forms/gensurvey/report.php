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
function gensurvey_report( $pid, $encounter, $cols, $id) {
    $count = 0;
/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_gensurvey';


/* an array of all of the fields' names and their types. */
$field_names = array('pts_name' => 'textfield','gens_date' => 'date','genu_age' => 'textfield','geny_examined' => 'provider','genr_higher' => 'textfield','fc_facies' => 'textfield','bu_build' => 'dropdown_list','nu_nutri' => 'textfield','decu_gen' => 'dropdown_list','sur_pal' => 'dropdown_list','cya_cyanosis' => 'dropdown_list','cl_clubbing' => 'dropdown_list','gr_clubgrade' => 'dropdown_list','ic_icterus' => 'dropdown_list','oe_oedema' => 'dropdown_list','loc_locoedema' => 'dropdown_list','vit_vitals' => 'dropdown_list','no_node' => 'textfield','ne_vein' => 'textfield','sk_skin' => 'textfield','sku_skull' => 'textfield','ex_extra' => 'textfield','imp_impression' => 'textfield');/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
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
            

            if ($key == 'pts_name' ) 
            { 
                echo xl_layout_label('Patient Name').":";
            }

            if ($key == 'gens_date' ) 
            { 
                echo xl_layout_label('Date of Visit').":";
            }

            if ($key == 'genu_age' ) 
            { 
                echo xl_layout_label('Age').":";
            }

            if ($key == 'geny_examined' ) 
            { 
                echo xl_layout_label('Examined By').":";
            }

            if ($key == 'genr_higher' ) 
            { 
                echo xl_layout_label('Higher Functions').":";
            }

            if ($key == 'fc_facies' ) 
            { 
                echo xl_layout_label('Facies').":";
            }

            if ($key == 'bu_build' ) 
            { 
                echo xl_layout_label('Build').":";
            }

            if ($key == 'nu_nutri' ) 
            { 
                echo xl_layout_label('Nutrition').":";
            }

            if ($key == 'decu_gen' ) 
            { 
                echo xl_layout_label('Decubitus').":";
            }

            if ($key == 'sur_pal' ) 
            { 
                echo xl_layout_label('Pallor').":";
            }

            if ($key == 'cya_cyanosis' ) 
            { 
                echo xl_layout_label('Cyanosis').":";
            }

            if ($key == 'cl_clubbing' ) 
            { 
                echo xl_layout_label('Clubbing').":";
            }

            if ($key == 'gr_clubgrade' ) 
            { 
                echo xl_layout_label('Grade of Clubbing').":";
            }

            if ($key == 'ic_icterus' ) 
            { 
                echo xl_layout_label('Icterus').":";
            }

            if ($key == 'oe_oedema' ) 
            { 
                echo xl_layout_label('Oedema').":";
            }

            if ($key == 'loc_locoedema' ) 
            { 
                echo xl_layout_label('Oedema LOcation').":";
            }

            if ($key == 'vit_vitals' ) 
            { 
                echo xl_layout_label('Vitals Recorded').":";
            }

            if ($key == 'no_node' ) 
            { 
                echo xl_layout_label('Lymph Nodes').":";
            }

            if ($key == 'ne_vein' ) 
            { 
                echo xl_layout_label('Neck Vein').":";
            }

            if ($key == 'sk_skin' ) 
            { 
                echo xl_layout_label('Skin and Appendages').":";
            }

            if ($key == 'sku_skull' ) 
            { 
                echo xl_layout_label('Cranium and Spine').":";
            }

            if ($key == 'ex_extra' ) 
            { 
                echo xl_layout_label('Extra Points').":";
            }

            if ($key == 'imp_impression' ) 
            { 
                echo xl_layout_label('Impression').":";
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

