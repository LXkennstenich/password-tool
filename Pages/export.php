<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ------------------------------------------------- Verfügbare Objekte / Variablen ------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */

/* @var $factory Factory */
/* @var $session Session */
/* @var $system System */
/* @var $account Account */
/* @var $encryption Encryption */
/* @var $options Options */
/* @var $sessionUID string */
/* @var $sessionUsername string */
/* @var $sessionIP string */
/* @var $sessionToken string */
/* @var $sessionTimestamp string */
/* @var $sessionAccessLevel string */
/* @var $searchTerm string */
/* @var $isSearch string */
/* @var $host string */
/* @var $userAgent string */

/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
if (!defined('PASSTOOL')) {
    die();
}

ob_flush();
ob_start();

$pdf = new FPDF();

$pdf->SetAuthor('PasswordTool');
$pdf->SetTitle('PasswordExport');
$pdf->SetCreator('PasswordTool');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Passwort Export');
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$datasets = $factory->getDatasets($sessionUID);

/* @var $dataset \Dataset */
foreach ($datasets as $dataset) {
    $dataset->decrypt();
    $pdf->Ln();
    $pdf->Cell(40, 10, '-----------------------------------------------------------------------');
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(40, 10, $dataset->getTitle());
    $pdf->Ln();
    $pdf->Cell(40, 10, $dataset->getLogin());
    $pdf->Ln();
    $pdf->Cell(40, 10, $dataset->getPassword());
    $pdf->Ln();
    $pdf->Cell(40, 10, $dataset->getUrl());
    $pdf->Ln();
    $pdf->Cell(40, 10, $dataset->getProject());
    unset($dataset);
}

$pdf->Close();

unset($datasets);

$pdf->Output('I', 'PasswordExport.pdf', true);

$html = ob_get_clean();

echo $html;
