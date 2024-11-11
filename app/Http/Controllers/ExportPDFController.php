<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemsTransaction;
use App\Models\Rentee;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ExportPDFController extends Controller
{
    public function analyticsExportPDF(Request $request)
    {
        $barChartImage = $request->input('barChartImageInput');
        $pieChartImage = $request->input('pieChartImageInput');
        $currentYear = $request->input('currentYear', date('Y'));


        if (empty($barChartImage) || empty($pieChartImage)) {
            return response()->json(['error' => 'Missing base64 image data.'], 400);
        }

        $barChartImageData = base64_decode(str_replace('data:image/png;base64,', '', $barChartImage));
        $pieChartImageData = base64_decode(str_replace('data:image/png;base64,', '', $pieChartImage));

        if ($barChartImageData === false || $pieChartImageData === false) {
            return response()->json(['error' => 'Failed to decode base64 image data.'], 400);
        }

        $publicPath = public_path('temp');
        $barChartFilename = 'barChart_' . Str::random(10) . '.png';
        $pieChartFilename = 'pieChart_' . Str::random(10) . '.png';

        if (!is_dir($publicPath)) {
            if (!mkdir($publicPath, 0755, true)) {
                return response()->json(['error' => 'Failed to create temp directory.'], 500);
            }
        }

        file_put_contents($publicPath . '/' . $barChartFilename, $barChartImageData);
        file_put_contents($publicPath . '/' . $pieChartFilename, $pieChartImageData);

        $usersCount = User::all()->count();
        $renteesCount = Rentee::all()->count();
        $itemsCount = Item::all()->count();
        $categoriesCount = Category::all()->count();
        $adminsCount = User::role('admin')->count();
        $superadminsCount = User::role('superadmin')->count();
        $cashiersCount = User::role('cashier')->count();
        $staffsCount = User::role('staff')->count();

        $itemsCanceledCount = ItemsTransaction::whereNotNull('canceledByRentee_at')
            ->whereYear('canceledByRentee_at', $currentYear)
            ->count();

        $itemsDeclinedCount = ItemsTransaction::whereNotNull('declinedByAdmin_at')
            ->whereYear('declinedByAdmin_at', $currentYear)
            ->count();

        $itemsCompletedCount = ItemsTransaction::whereNotNull('returned_at')
            ->whereYear('returned_at', $currentYear)
            ->count();

        $pdfData = [
            'usersCount' => $usersCount,
            'renteesCount' => $renteesCount,
            'itemsCount' => $itemsCount,
            'categoriesCount' => $categoriesCount,
            'adminsCount' => $adminsCount,
            'superadminsCount' => $superadminsCount,
            'cashiersCount' => $cashiersCount,
            'staffsCount' => $staffsCount,
            'barChartImage' => $barChartFilename,
            'pieChartImage' => $pieChartFilename,
            'currentYear' => $currentYear,
            'itemsCanceledCount' => $itemsCanceledCount,
            'itemsDeclinedCount' => $itemsDeclinedCount,
            'itemsCompletedCount' => $itemsCompletedCount,
        ];

        $pdf = PDF::loadView('admin.exports.analytics-pdf', $pdfData);

        if ($request->action == 'view') {
            return response()->stream(function () use ($pdf, $publicPath, $barChartFilename, $pieChartFilename) {
                echo $pdf->output();
                unlink($publicPath . '/' . $barChartFilename);
                unlink($publicPath . '/' . $pieChartFilename);
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="analytics-report-' . $currentYear . '.pdf"'
            ]);
        } elseif ($request->action == 'download') {
            $tempFile = tempnam(sys_get_temp_dir(), 'analytics_report_');
            file_put_contents($tempFile, $pdf->output());

            $response = response()->download($tempFile, 'analytics-report-' . $currentYear . '.pdf');

            $response->deleteFileAfterSend(true);

            unlink($publicPath . '/' . $barChartFilename);
            unlink($publicPath . '/' . $pieChartFilename);

            return $response;
        }
    }
}
