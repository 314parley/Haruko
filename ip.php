<?
function getIP()
{
    $fields = array('HTTP_X_FORWARDED_FOR',
                    'REMOTE_ADDR',
                    'HTTP_CF_CONNECTING_IP',
                    'HTTP_X_CLUSTER_CLIENT_IP');

    foreach($fields as $f)
    {
        $tries = $_SERVER[$f];
        if (empty($tries))
            continue;
        $tries = explode(',',$tries);
        foreach($tries as $try)
        {
            $r = filter_var($try,
                            FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 |
                            FILTER_FLAG_NO_PRIV_RANGE |
                            FILTER_FLAG_NO_RES_RANGE);

            if ($r !== false)
            {
                return $try;
            }
        }
    }
    return false;
}
echo getIP();
?>
