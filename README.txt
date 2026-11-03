ONLINE REGISTRATION FORM — PHP / HTML / CSS / JavaScript / jQuery

FILES
- index.php   Registration form
- submit.php  Server-side validation and formatted confirmation page
- style.css   Responsive styling and print stylesheet
- script.js   jQuery client-side validation

RUN LOCALLY
1. Install XAMPP (includes Apache, PHP and MySQL).
2. Copy the registration_app folder into C:\xampp\htdocs\
3. Open XAMPP Control Panel and start Apache.
4. Visit http://localhost/registration_app/

IMPORTANT
- This starter project validates and displays submitted data. It does NOT save applications.
- Do not collect sensitive personal information in a public demo.
- For production, add database storage (prepared statements), HTTPS, spam protection,
  privacy notice, access controls, backups and a retention policy.
- The form uses jQuery from the official jQuery CDN, so internet access is needed
  for the client-side validation script. Server-side validation remains active.

DEPLOYMENT
Upload the files to the public web directory (often public_html) of a PHP hosting
provider. Keep index.php and submit.php together. Make sure the host supports a
currently supported PHP 8.x version and HTTPS. Test with dummy data after publishing.
