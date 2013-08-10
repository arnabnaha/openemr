<?php

// Copyright (C) 2005-2006 Rod Roark <rod@sunsetsystems.com>
//
// Windows compatibility mods 2009 Bill Cernansky [mi-squared.com]
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Updated by Medical Information Integration, LLC to support download
//  and multi OS use - tony@mi-squared..com 12-2009
//
// Format further modified by David Martelle hightechhelper@gmail.com 
//
//////////////////////////////////////////////////////////////////////
// This is a template for printing patient statements and collection
// letters.  You must customize it to suit your practice.  If your
// needs are simple then you do not need programming experience to do
// this - just read the comments and make appropriate substitutions.
// All you really need to do is replace the [strings in brackets].
//////////////////////////////////////////////////////////////////////

// The location/name of a temporary file to hold printable statements.
//

$STMT_TEMP_FILE = $GLOBALS['temporary_files_dir'] . "/openemr_statements.txt";
$STMT_TEMP_FILE_PDF = $GLOBALS['temporary_files_dir'] . "/openemr_statements.pdf";

$STMT_PRINT_CMD = $GLOBALS['print_command']; 

// This function builds a printable statement or collection letter from
// an associative array having the following keys:
//
//  today   = statement date yyyy-mm-dd
//  pid     = patient ID
//  patient = patient name
//  amount  = total amount due
//  to      = array of addressee name/address lines
//  lines   = array of lines, each with the following keys:
//    dos     = date of service yyyy-mm-dd
//    desc    = description
//    amount  = charge less adjustments
//    paid    = amount paid
//    notice  = 1 for first notice, 2 for second, etc.  
//    detail  = associative array of details
//
// Each detail array is keyed on a string beginning with a date in
// yyyy-mm-dd format, or blanks in the case of the original charge
// items.  Its values are associative arrays like this:
//
//  pmt - payment amount as a positive number, only for payments
//  src - check number or other source, only for payments
//  chg - invoice line item amount amount, only for charges or
//        adjustments (adjustments may be zero)
//  rsn - adjustment reason, only for adjustments
//
// The returned value is a string that can be sent to a printer.
// This example is plain text, but if you are a hotshot programmer
// then you could make a PDF or PostScript or whatever peels your
// banana.  These strings are sent in succession, so append a form
// feed if that is appropriate.
//

//
// I have made the statement look simpler by removing the Visa/crediut card payment option fromn the 
// statement. In India, we dont send payment info at home to be paid later, we take it on the same day
// and insurance is also handled in a different way - Arnab Naha
//
// A sample of the text based format follows:
//             (put date on it's own line)               2009-12-29
//
// CLINIC ADDRESS                  PATIENT
//
//[Your Clinic Name]               Patient Name          
//[Your Clinic Address]            Chart Number: 1848        
//[City, State Zip]                Total amount due: 147.40  (added total due amt)
//
//_______________________ STATEMENT SUMMARY _______________________
//
//Visit Date  Description                                    Amount
//
//2009-08-20  Procedure 99345                                198.90
//            Paid 2009-12-15:                               -51.50
//... more details ...
//...
//...
// skipping blanks in example
//
//
//Name: Patient Name              Date: 2009-12-29     Due:   147.40
//_________________________________________________________________
//
//Thank you, We appreciate prompt payment with no dues.    (altered wording)
//Contact Us for any discrepancy.
//[Your billing contact name]
//Billing Department
//[billing_phone] (If billing number is different from facility, put it in line 263, otherwise
// remove remark double slashes on line 278 and remark out line 279 ) 

function create_statement($stmt) {
 if (! $stmt['pid']) return ""; // get out if no data

 // These are your clinics return address, contact etc.  Edit them.
 // TBD: read this from the facility table
 
 // Facility (service location)
  $atres = sqlStatement("select f.name,f.street,f.city,f.state,f.postal_code from facility f " .
    " left join users u on f.id=u.facility_id " .
    " left join  billing b on b.provider_id=u.id and b.pid = '".$stmt['pid']."' " .
    " where  service_location=1");
  $row = sqlFetchArray($atres);
 
 // Facility (service location)
 
 $clinic_name = "{$row['name']}";
 $clinic_addr = "{$row['street']}";
 $clinic_csz = "{$row['city']}, {$row['state']}, {$row['postal_code']}";
 
 
 // Billing location
 $remit_name = $clinic_name;
 $remit_addr = $clinic_addr;
 $remit_csz = $clinic_csz;
 
 // Contacts
  $atres = sqlStatement("select f.attn,f.phone from facility f " .
    " left join users u on f.id=u.facility_id " .
    " left join  billing b on b.provider_id=u.id and b.pid = '".$stmt['pid']."'  " .
    " where billing_location=1");
  $row = sqlFetchArray($atres);
 $billing_contact = "{$row['attn']}";
 $billing_phone = "{$row['phone']}";

 // Text only labels
 
 $label_addressee = xl('ADDRESSEE');
 $label_remitto = xl('REMIT TO');
 $label_chartnum = xl('Chart Number');
 $label_totaldue = xl('Total amount due');
 $label_payby = xl('To pay by VISA / MC / AMEX / Disc - Circle one and fill in blanks.');
 $label_cardnum = xl('Card No.');  
 $label_expiry = xl('Exp');
 $label_billzip = xl('Billing Zip Code');
 $label_cardccv = xl('CCV');
 $label_sign = xl('Signature');
 $label_retpay = xl('Cut on dotted line and return this portion with your payment.');
 $label_pgbrk = xl('STATEMENT SUMMARY');
 $label_visit = xl('Visit Date');
 $label_desc = xl('Description');
 $label_amt = xl('Amount');
 $label_claddress = xl('CLINIC ADDRESS');
 $label_patient = xl('PATIENT');

 // This is the text for the top part of the page, up to but not
 // including the detail lines.  Some examples of variable fields are:
 //  %s    = string with no minimum width
 //  %9s   = right-justified string of 9 characters padded with spaces
 //  %-25s = left-justified string of 25 characters padded with spaces
 // Note that "\n" is a line feed (new line) character.
 // reformatted to handle i8n by tony
 
$out .= sprintf("%65s\n",$stmt['today']);
$out .= "\n\n";
$out .= sprintf("%-44s %s\n",$label_claddress,$label_patient);
$out .= "\n";
$out .= sprintf("%-44s %-23s\n",$clinic_name,$stmt['patient']);
$out .= sprintf("%-44s %s: %-s\n",$clinic_addr,$label_chartnum,$stmt['pid']);
$out .= sprintf("%-44s %s: %-s\n",$clinic_csz,$label_totaldue,$stmt['amount']);
$out .= "\n";
//$out .= sprintf("     %-36s %-s\n",$label_addressee,$label_remitto);
//$out .= sprintf("     %-36s %s\n",$stmt['to'][0],$remit_name);
//$out .= sprintf("     %-36s %s\n",$stmt['to'][1],$remit_addr);
//$out .= sprintf("     %-36s %s\n",$stmt['to'][2],$remit_csz);

if($stmt['to'][3]!='')//to avoid double blank lines the if condition is put.
 	$out .= sprintf("   %-32s\n",$stmt['to'][3]);
$out .= "\n";
$out .= sprintf("_________________________________________________________________\n");
//$out .= sprintf("%-66s\n",$label_payby);
//$out .= sprintf("%s_______________________________________ %s______________ \n",
//                $label_cardnum,$label_expiry);
//$out .= sprintf("%s_________________ %s____________\n",
//                $label_billzip,$label_cardccv);
//$out .= "Note; CCV is a 3 digit code on back except Amex = 4 on front.\n";
//$out .= "\n";
//$out .= sprintf("%s____________________________________ Date _______________\n",
//               $label_sign);
//$out .= sprintf("%-3s %s\n",null,$label_retpay);
//$out .= sprintf("-----------------------------------------------------------------\n");
//$out .= "\n";
$out .= sprintf("_______________________ %s _______________________\n",$label_pgbrk);
$out .= "\n";
$out .= sprintf("%-11s %-46s %s\n",$label_visit,$label_desc,$label_amt);
$out .= "\n";
 
 // This must be set to the number of lines generated above.
 //
 $count = 25;

 // This generates the detail lines.  Again, note that the values must
 // be specified in the order used.
 //
 foreach ($stmt['lines'] as $line) {
  $description = $line['desc'];
  $tmp = substr($description, 0, 14);
  if ($tmp == 'Procedure 9920' || $tmp == 'Procedure 9921')
   $description = xl('Office Visit');

  $dos = $line['dos'];
  ksort($line['detail']);

  foreach ($line['detail'] as $dkey => $ddata) {
   $ddate = substr($dkey, 0, 10);
   if (preg_match('/^(\d\d\d\d)(\d\d)(\d\d)\s*$/', $ddate, $matches)) {
    $ddate = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
   }
   $amount = '';

   if ($ddata['pmt']) {
    $amount = sprintf("%.2f", 0 - $ddata['pmt']);
    $desc = xl('Paid') .' '. $ddate .': '. $ddata['src'].' '. $ddata['insurance_company'];
   } else if ($ddata['rsn']) {
    if ($ddata['chg']) {
     $amount = sprintf("%.2f", $ddata['chg']);
     $desc = xl('Adj') .' '.  $ddate .': ' . $ddata['rsn'].' '. $ddata['insurance_company'];
    } else {
     $desc = xl('Note') .' '. $ddate .': '. $ddata['rsn'].' '. $ddata['insurance_company'];
    }
   } else if ($ddata['chg'] < 0) {
    $amount = sprintf("%.2f", $ddata['chg']);
    $desc = xl('Patient Payment');
   } else {
    $amount = sprintf("%.2f", $ddata['chg']);
    $desc = $description;
   }

   $out .= sprintf("%-10s  %-45s%8s\n", $dos, $desc, $amount);
   $dos = '';
   ++$count;
  }
 }

 // This generates blank lines until we are at line 50.
 //
 while ($count++ < 50) $out .= "\n";

 // Fixed text labels  Edit your parting shots in this section
 $label_ptname = xl('Name');
 $label_today = xl('Date');
 $label_due = xl('Due');
 $label_thanks = xl('Thank you, We appreciate prompt payment with no dues.');
 $label_acstat = xl('Contact Us for any discrepancy.');
 $label_prompt = xl('Days; 0-30=Current, 30-59=Overdue, 60-89=Delinquent, >90=To Collections');
 $label_dept = xl('Billing Department');
 $label_bphone = xl('For billing questions call -03325396068-');
 
 // This is the bottom portion of the page.
 
 $out .= sprintf("%-s: %-25s %-s: %-14s %-s: %8s\n",$label_ptname,$stmt['patient'],
                 $label_today,$stmt['today'],$label_due,$stmt['amount']);
 $out .= sprintf("_______________________________________________________________________\n");
 $out .= sprintf("%-s\n",$label_thanks);
 $out .= "\n";
 $out .= sprintf("%-s\n",$label_acstat);
 //$out .= sprintf("%-s\n",$label_prompt);
 $out .= "\n";
 $out .= sprintf("%-s\n",$billing_contact);
 $out .= sprintf("%-s\n",$label_dept);
 // (The following line inserts facility phone number, which may not be the billing phone)
 //$out .= sprintf("%-s\n",$billing_phone); 
 $out .= sprintf("%-s\n",$label_bphone);
 $out .= "\014"; // this is a form feed
 
 return $out;
}
?>
