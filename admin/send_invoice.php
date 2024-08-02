<?php
function send_invoice($no_wa, $link){
    $token = "2v9uposHjGciSz8N@4cY";
    $target = $no_wa;
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.fonnte.com/send',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
    'target' => $target,
    'message' => 'Halo customer, reservasi anda telah kami terima dan telah kami proses, klik link berikut untuk melihat resi pembayaran : '.$link
    ),
    CURLOPT_HTTPHEADER => array(
        "Authorization: $token"
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    //echo $response;
}
    