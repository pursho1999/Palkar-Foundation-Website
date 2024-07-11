<?php
function ord_id($uId)
{
    return 'ORD_' . $uId . random_int(1111111111, 99999999999);
}

// Function to generate transaction ID
function trans_id()
{
    return 'TXN_' . uniqid();
}
