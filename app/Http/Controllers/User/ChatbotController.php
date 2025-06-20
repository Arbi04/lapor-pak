<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\ReportRepositoryInterface;
use App\Interfaces\ReportCategoryRepositoryInterface;

class ChatbotController extends Controller
{
    private ReportRepositoryInterface $reportRepository;
    private ReportCategoryRepositoryInterface $reportCategoryRepository;

    public function __construct(
        ReportRepositoryInterface $reportRepository,
        ReportCategoryRepositoryInterface $reportCategoryRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportCategoryRepository = $reportCategoryRepository;
    }

    public function index()
    {
        return view('pages.app.chatbot');
    }

    public function processCommand(Request $request)
    {
        $message = strtolower(trim($request->input('message')));
        $user = Auth::user();

        // Command untuk informasi laporan
        if (strpos($message, 'status laporan') !== false || strpos($message, 'laporan saya') !== false) {
            return $this->getReportStatus($user);
        }

        // Command untuk membuat laporan baru
        if (strpos($message, 'buat laporan') !== false || strpos($message, 'lapor') !== false) {
            return $this->createReportGuidance();
        }

        // Command untuk melihat kategori
        if (strpos($message, 'kategori') !== false) {
            return $this->getCategories();
        }

        // Command untuk bantuan
        if (strpos($message, 'bantuan') !== false || strpos($message, 'help') !== false) {
            return $this->getHelp();
        }

        // Forward ke AI chatbot eksternal
        return $this->forwardToAI($message);
    }

    private function getReportStatus($user)
    {
        if (!$user) {
            return response()->json([
                'response' => 'Silakan login terlebih dahulu untuk melihat status laporan Anda.',
                'action' => 'redirect',
                'url' => route('login')
            ]);
        }

        $reports = $this->reportRepository->getReportsByResidentId('all');
        $activeReports = $reports->whereIn('reportStatuses.last.status', ['delivered', 'in_process'])->count();
        $completedReports = $reports->where('reportStatuses.last.status', 'completed')->count();

        $response = "📊 **Status Laporan Anda:**\n\n";
        $response .= "🔄 Laporan Aktif: {$activeReports}\n";
        $response .= "✅ Laporan Selesai: {$completedReports}\n\n";
        $response .= "Klik tombol di bawah untuk melihat detail laporan Anda.";

        return response()->json([
            'response' => $response,
            'action' => 'show_buttons',
            'buttons' => [
                [
                    'text' => 'Lihat Semua Laporan',
                    'url' => route('report.myreport', ['status' => 'delivered'])
                ]
            ]
        ]);
    }

    private function createReportGuidance()
    {
        $response = "📝 **Panduan Membuat Laporan:**\n\n";
        $response .= "1. 📸 Ambil foto bukti masalah\n";
        $response .= "2. 📍 Tentukan lokasi kejadian\n";
        $response .= "3. 📋 Pilih kategori yang sesuai\n";
        $response .= "4. ✍️ Tulis deskripsi lengkap\n\n";
        $response .= "Klik tombol di bawah untuk mulai membuat laporan:";

        return response()->json([
            'response' => $response,
            'action' => 'show_buttons',
            'buttons' => [
                [
                    'text' => '📸 Ambil Foto & Buat Laporan',
                    'url' => route('report.take')
                ],
                [
                    'text' => '📋 Lihat Panduan Lengkap',
                    'url' => '#'
                ]
            ]
        ]);
    }

    private function getCategories()
    {
        $categories = $this->reportCategoryRepository->getAllReportCategories();

        $response = "📂 **Kategori Laporan yang Tersedia:**\n\n";
        foreach ($categories as $index => $category) {
            $response .= ($index + 1) . ". " . $category->name . "\n";
        }
        $response .= "\nPilih kategori yang sesuai saat membuat laporan.";

        return response()->json([
            'response' => $response,
            'action' => 'show_buttons',
            'buttons' => [
                [
                    'text' => 'Buat Laporan Sekarang',
                    'url' => route('report.take')
                ]
            ]
        ]);
    }

    private function getHelp()
    {
        $response = "🤖 **Bantuan Chatbot Lapor Pak:**\n\n";
        $response .= "**Perintah yang bisa Anda gunakan:**\n";
        $response .= "• 'status laporan' - Cek status laporan Anda\n";
        $response .= "• 'buat laporan' - Panduan membuat laporan\n";
        $response .= "• 'kategori' - Lihat kategori laporan\n";
        $response .= "• 'bantuan' - Tampilkan menu ini\n\n";
        $response .= "**Menu Cepat:**";

        return response()->json([
            'response' => $response,
            'action' => 'show_buttons',
            'buttons' => [
                [
                    'text' => '📊 Status Laporan',
                    'action' => 'status laporan'
                ],
                [
                    'text' => '📝 Buat Laporan',
                    'url' => route('report.take')
                ],
                [
                    'text' => '👤 Profil Saya',
                    'url' => route('profile')
                ]
            ]
        ]);
    }

    private function forwardToAI($message)
    {
        // Forward ke AI chatbot eksternal
        try {
            $response = $this->callExternalChatbot($message);
            return response()->json(['response' => $response]);
        } catch (\Exception $e) {
            return response()->json([
                'response' => 'Maaf, saya tidak mengerti perintah tersebut. Ketik "bantuan" untuk melihat perintah yang tersedia.'
            ]);
        }
    }

    private function callExternalChatbot($message)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:5000/chatbot');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $result) {
            $data = json_decode($result, true);
            return $data['response'] ?? 'Tidak dapat memproses permintaan Anda.';
        }

        throw new \Exception('External chatbot error');
    }
}
