moodle-auth_drupalconnect
=========================

Moodle Module to handle authentication via REST interface of a drupal platform.

This module is working togehter with a Drupal module called "MOODLEconnect"
which is providing a possibility to add moodle servers to a drupal system:
http://drupal.org/project/moodleconnect

This is not only a SSO Solution. Drupal is handling user creation in Moodle and
connecting this users to moodle courses managed by access rules to course nodes
in drupal.
Together with MOODLEconnect in the drupal system the users are authenticated
with onetime/shorttime - login links created and presented in the drupal
system.

# Core Modification for better security
Currently the moodle webservice cannot be normally overwritten. But especially
the webservice user account needs a lot of administration permissions. So this
solution needs a core modification to change the webservice authentication for
using also the DRUPALconnect One Time Password system until this is fixed in
moodle core system.
It's just a small addition in the file "auth/webservice/auth.php". If a
webservice user account is marked with the "drupalconnect" auth method it is be
used.
## You can change this file in two ways.
1. The easy git way
git apply -v auth/drupalconnect/patch/auth_webservice.diff
2. Rename and copy way
   from:    auth/drupalconnect/patch/auth_webservice_auth-php.txt
   to:      auth/webservice/auth.php
This change has to be done again after a system update. If you are doing
updates with git the additional git apply is very easy.

# Configurations
Because in this solution the moodle system is follwing the drupal system you
need at first some data provided by the drupal system.
The drupal system is telling you how the webservice user has to be named.
This user hast to be modified to autheticate against the "drupalconnect" auth
method and all permissions to create and modify users and courses and of course
to use the webservice.
