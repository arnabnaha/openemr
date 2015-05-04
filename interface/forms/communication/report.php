<?php
/*
 * this file's contents are included in both the encounter page as a 'quick summary' of a form, and in the medical records' reports page.
 */

/* for $GLOBALS[], ?? */
require_once(dirname(__FILE__).'/../../globals.php');
/* for acl_check(), ?? */
require_once($GLOBALS['srcdir'].'/api.inc');
/* for generate_display_field() */
require_once($GLOBALS['srcdir'].'/options.inc.php');
/* The name of the function is significant and must match the folder name */
function communication_report( $pid, $encounter, $cols, $id) {
    $count = 0;
/** CHANGE THIS - name of the database table associated with this form **/
$table_name = 'form_communication';


/* an array of all of the fields' names and their types. */
$field_names = array('contact_date' => 'date','contact_name' => 'textfield','phone' => 'textfield','direction' => 'checkbox_list','contact_success' => 'checkbox_list','reason' => 'textarea','result' => 'textarea','screener' => 'provider','signature_box' => 'textfield');/* in order to use the layout engine's draw functions, we need a fake table of layout data. */
$manual_layouts = array( 
 'contact_date' => 
   array( 'field_id' => 'contact_date',
          'data_type' => '4',
          'fld_length' => '0',
          'description' => 'Date contact occured/was attempted',
          'list_id' => '' ),
 'contact_name' => 
   array( 'field_id' => 'contact_name',
          'data_type' => '2',
          'fld_length' => '10',
          'max_length' => '255',
          'description' => 'Person we are attempting to contact/were contacted by',
          'list_id' => '' ),
 'phone' => 
   array( 'field_id' => 'phone',
          'data_type' => '2',
          'fld_length' => '10',
          'max_length' => '15',
          'description' => 'Phone number dialed or number of caller(if known)',
          'list_id' => '' ),
 'direction' => 
   array( 'field_id' => 'direction',
          'data_type' => '21',
          'fld_length' => '0',
          'description' => 'Was the call outbound?',
          'list_id' => 'yesno' ),
 'contact_success' => 
   array( 'field_id' => 'contact_success',
          'data_type' => '21',
          'fld_length' => '0',
          'description' => 'If the call was outbound, did you get ahold of someone who could help?',
          'list_id' => 'yesno' ),
 'reason' => 
   array( 'field_id' => 'reason',
          'data_type' => '3',
          'fld_length' => '10',
          'max_length' => '3',
          'description' => 'the principal reason or reasons contact was attempted',
          'list_id' => '' ),
 'result' => 
   array( 'field_id' => 'result',
          'data_type' => '3',
          'fld_length' => '10',
          'max_length' => '3',
          'description' => '',
          'list_id' => '' ),
 'screener' => 
   array( 'field_id' => 'screener',
          'data_type' => '10',
          'fld_length' => '0',
          'description' => 'Staff Member',
          'list_id' => '' ),
 'signature_box' => 
   array( 'field_id' => 'signature_box',
          'data_type' => '2',
          'fld_length' => '10',
          'max_length' => '60',
          'description' => 'Sign here to signify all information in this form is correct',
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
            

            if ($key == 'contact_date' ) 
            { 
                echo xl_layout_label('Date').":";
            }

            if ($key == 'contact_name' ) 
            { 
                echo xl_layout_label('Name').":";
            }

            if ($key == 'phone' ) 
            { 
                echo xl_layout_label('Phone #').":";
            }

            if ($key == 'direction' ) 
            { 
                echo xl_layout_label('Outgoing Call?').":";
            }

            if ($key == 'contact_success' ) 
            { 
                echo xl_layout_label('Contact Successful?').":";
            }

            if ($key == 'reason' ) 
            { 
                echo xl_layout_label('Reasons for contact').":";
            }

            if ($key == 'result' ) 
            { 
                echo xl_layout_label('Results of conversation').":";
            }

            if ($key == 'screener' ) 
            { 
                echo xl_layout_label('Access Screener').":";
            }

            if ($key == 'signature_box' ) 
            { 
                echo xl_layout_label('Signature').":";
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

