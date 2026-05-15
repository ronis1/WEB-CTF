<?php
header('Content-Type: application/x-pem-file');
echo file_get_contents('/app/keys/public.pem');
