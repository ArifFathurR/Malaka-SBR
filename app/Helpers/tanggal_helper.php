<?php
function formatTanggalLengkap($datetime)
{
    $bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $timestamp = strtotime($datetime);
    $tgl = date('d', $timestamp);
    $bln = $bulan[(int)date('m', $timestamp) - 1];
    $thn = date('Y', $timestamp);
    $jam = date('H:i:s', $timestamp);

    return "$tgl $bln $thn $jam";
}

