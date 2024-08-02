<?php
function send_wa($no_wa){
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
    'message' => 'Halo customer, ini dari 3R garage mengonfirmasi untuk penjemputan motor anda untuk perbaikan di bengkel berdasarkan reservasi anda di website kami. Silahkan kirimkan lokasi penjemputan anda'
    ),
    CURLOPT_HTTPHEADER => array(
        "Authorization: $token"
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    //echo $response;
}
    