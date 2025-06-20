<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\ReportRepositoryInterface;
use App\Interfaces\ReportCategoryRepositoryInterface;

class HomeController extends Controller
{
    private ReportRepositoryInterface $reportRepository;
    private ReportCategoryRepositoryInterface $reportCategoryRepository;

    public function __construct(
        ReportRepositoryInterface $reportRepository,
        ReportCategoryRepositoryInterface $reportCategoryRepository,
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportCategoryRepository = $reportCategoryRepository;
    }

    public function index()
    {
        $reports = $this->reportRepository->getLatestReports();
        $categories = $this->reportCategoryRepository->getAllReportCategories();

        return view('pages.app.home', compact('categories', 'reports'));
    }
}
