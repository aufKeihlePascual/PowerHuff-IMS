<?php
namespace App\Controllers;
require 'vendor/autoload.php';

use App\Models\Activity;
use Fpdf\Fpdf;

class GeneratePDFController extends BaseController
{
    private $reportConfigs, $activityModel;

    public function __construct()
    {
        $this->activityModel = new \App\Models\Activity();

        $this->reportConfigs = [
            'stock_activity' => [
                'model' => new Activity(),
                'method' => 'getUserActivities',
                'filters' => ['activity_type' => 'stock'],
                'title' => 'Stock Activity Report',
                'headers' => ['Username', 'Product', 'Category', 'Change Type', 'Qty', 'Logged'],
                'fields' => ['username', 'product_name', 'category_name', 'stock_change_type', 'stock_change_quantity', 'stock_logged_on']
            ],
            'product_activity' => [
                'model' => new Activity(),
                'method' => 'getUserActivities',
                'filters' => ['activity_type' => 'product'],
                'title' => 'Product Activity Report',
                'headers' => ['Username', 'Product', 'Category', 'Activity', 'Qty', 'Logged'],
                'fields' => ['username', 'product_name', 'category_name', 'product_activity_type', 'stock_change_quantity', 'product_logged_on']
            ],
        ];
    }

    public function handlePdfRequest($type)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['generate_pdf'])) {
            echo "Invalid request!";
            exit;
        }
    
        if (!isset($this->reportConfigs[$type])) {
            echo "Invalid report type!";
            exit;
        }
    
        $config = $this->reportConfigs[$type];
        $model = $config['model'];
        $method = $config['method'];
        $filters = $config['filters'] ?? [];
    
        // $data = $model->$method($filters);
        // var_dump($data);
        $data = $this->activityModel->getUserActivities();
        // var_dump($data);
        // exit;

        $this->generatePdf($data, $type);
        
    }

    public function generatePdf($data, $type)
    {
         $columnWidths = [
            40,
            60,
            50,
            40,
            30,
            55
        ];
        
        $reportConfig = $this->reportConfigs[$type];

        $pdf = new Fpdf('L');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Cell(280, 10, $reportConfig['title'], 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 12);
        $i = 0;

        foreach ($reportConfig['headers'] as $header) {
            $pdf->Cell($columnWidths[$i], 10, $header, 1, 0, 'C');
            $i++;
        }
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($data as $row) {
            $i = 0;
            foreach ($reportConfig['fields'] as $field) {
                $value = isset($row[$field]) ? $row[$field] : 'N/A';
                
                $pdf->Cell($columnWidths[$i], 10, $value, 1, 0, 'C');
                $i++; 
            }
            $pdf->Ln();
        }

        $pdf->Output('D', ucfirst($type) . '_Report.pdf');
        exit;
    }
}
