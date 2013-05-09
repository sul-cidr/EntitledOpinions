<?php

# Stanford - Set different session cookies for HTTP and HTTPS.
if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) {
   ini_set('session.cookie_secure', 1);
}

$conf['allow_authorize_operations'] = FALSE;
