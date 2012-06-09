##########
# README #
##########

This form was created by Women's Health Services IT dept. as a contribution to OpenEMR and therefore released under the GNU GPL license  questions can be addressed to it@whssf.org. For support we recommend you contact openemr.net

This form needs a directory called `scanned` hanging from `forms/documents/`
i.e.. `forms/documents/scanned`.
The  `scanned` dir will hold the documents under another directory that the script will create during upload time. This directory will be named after the $PID (patient id)
i.e..  `forms/documents/scanned/1` and the documents scanned for that patient will hang there.
It is important that you give Apache writing permission to  `scanned`.
After uploading the script will chmod the file so it can only be read.

The documents uploaded are renamed in a way that you will always be able to identify them. year-month-day-hour-minute-second-patient_id

The installation procedure is fairly simple.
Decompress the file under the `forms` directory, which you may already done if you are reading this.
Then log in OpenEMR, go to administration, forms, register (this form), install db, and enable.
All that should make the form available under encounters.

****EDITING NOTES******
  Edited Art Eaton, mostly with assistance from the OpenEMR gang on Sourceforge.
    The form now accepts a wider range of file formats including audio, video, pdf, text etc...
    The form no longer simply displays an image file, but instead displays a frame inside the encounter form table. 
    This allows a scaled view of the document, and you must use the link to view the document in full size.  This was to enable the display of pdf's and other formats.
    It is possible to edit the file "print.php" to give the display frame a permanent size by altering the frame size from 95% and 100% to a numeric value in pixels/units.
    This version is not particularly compatible with OpenEMR 4.0  It works great with 3.2.0.  

