<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('SMS_API_BASE_URL', 'https://api.ideal-study.uz');
        $this->apiKey = env('SMS_API_KEY', '');
    }

    /**
     * Get API headers with authentication
     */
    protected function getHeaders(): array
    {
        return [
            'X-API-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Get HTTP client with SSL verification disabled
     */
    protected function getHttpClient()
    {
        return Http::withoutVerifying();
    }

    /**
     * Send single SMS
     */
    public function sendStudent($number, $message)
    {
        $number = preg_replace('/\D/', '', $number);
        // Ensure number starts with country code
        if (!str_starts_with($number, '998')) {
            $number = '998' . ltrim($number, '0');
        }

        $response = $this->getHttpClient()
            ->withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/api/sms/send', [
                'phone' => $number,
                'message' => $message,
                'from' => '4546'
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'success' => false,
            'message' => 'SMS yuborishda xatolik',
            'error' => $response->json()
        ];
    }

    /**
     * Send receipt SMS
     */
    public function sendReceip($number, $name, $summa, $date, $month, $id)
    {
        $number = preg_replace('/\D/', '', $number);
        // Ensure number starts with country code
        if (!str_starts_with($number, '998')) {
            $number = '998' . ltrim($number, '0');
        }

        $message = "{$name} ga {$month} oyi uchun {$summa} so'm to'lov qabul qilindi. Sana {$date}";

        $response = $this->getHttpClient()
            ->withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/api/sms/send', [
                'phone' => $number,
                'message' => $message,
                'from' => '4546'
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'success' => false,
            'message' => 'SMS yuborishda xatolik',
            'error' => $response->json()
        ];
    }

    /**
     * Send SMS to multiple users (batch)
     */
    public function sendSMS($users, $message)
    {
        $messages = [];
        foreach ($users as $index => $user) {
            $phone = preg_replace('/\D/', '', $user->phone ?? $user);
            // Ensure number starts with country code
            if (!str_starts_with($phone, '998')) {
                $phone = '998' . ltrim($phone, '0');
            }

            $messages[] = [
                "user_sms_id" => "sms" . ($index + 1),
                "to" => $phone,
                "text" => $message,
            ];
        }

        if (empty($messages)) {
            return [
                'success' => false,
                'message' => 'Yuborish uchun xabar topilmadi'
            ];
        }

        $data = [
            "messages" => $messages,
            "from" => "4546",
            "dispatch_id" => time()
        ];

        $response = $this->getHttpClient()
            ->withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/api/sms/send-batch', $data);

        if ($response->successful()) {
            $result = $response->json();
            return [
                'status' => $result['success'] ? 'success' : 'error',
                'message' => $result['message'] ?? 'SMS yuborildi',
                'data' => $result['data'] ?? null
            ];
        }

        return [
            'status' => 'error',
            'message' => 'SMS yuborishda xatolik',
            'error' => $response->json()
        ];
    }

    /**
     * Send SMS to parents
     */
    public function sendSMSparents($users, $message)
    {
        $messages = [];
        foreach ($users as $index => $user) {
            $phone = preg_replace('/\D/', '', $user->parents_phone ?? $user->phone ?? $user);
            // Ensure number starts with country code
            if (!str_starts_with($phone, '998')) {
                $phone = '998' . ltrim($phone, '0');
            }

            $messages[] = [
                "user_sms_id" => "sms" . ($index + 1),
                "to" => $phone,
                "text" => $message,
            ];
        }

        if (empty($messages)) {
            return [
                'success' => false,
                'message' => 'Yuborish uchun xabar topilmadi'
            ];
        }

        $data = [
            "messages" => $messages,
            "from" => "4546",
            "dispatch_id" => time()
        ];

        $response = $this->getHttpClient()
            ->withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/api/sms/send-batch', $data);

        if ($response->successful()) {
            $result = $response->json();
            return [
                'status' => $result['success'] ? 'success' : 'error',
                'message' => $result['message'] ?? 'SMS yuborildi',
                'data' => $result['data'] ?? null
            ];
        }

        return [
            'status' => 'error',
            'message' => 'SMS yuborishda xatolik',
            'error' => $response->json()
        ];
    }

    /**
     * Send SMS to students by subject/class
     */
    public function sendSmsSubject($users, $message)
    {
        $messages = [];
        foreach ($users as $index => $user) {
            // Handle both direct phone and student object
            $phone = null;
            if (is_object($user) && isset($user->student)) {
                $phone = $user->student->phone ?? null;
            } elseif (is_object($user) && isset($user->phone)) {
                $phone = $user->phone;
            } elseif (is_string($user)) {
                $phone = $user;
            }

            if (!$phone) {
                continue;
            }

            $phone = preg_replace('/\D/', '', $phone);
            // Ensure number starts with country code
            if (!str_starts_with($phone, '998')) {
                $phone = '998' . ltrim($phone, '0');
            }

            $messages[] = [
                "user_sms_id" => "sms" . ($index + 1),
                "to" => $phone,
                "text" => $message,
            ];
        }

        if (empty($messages)) {
            return [
                'status' => 'error',
                'message' => 'Yuborish uchun xabar topilmadi'
            ];
        }

        $data = [
            "messages" => $messages,
            "from" => "4546",
            "dispatch_id" => time()
        ];

        $response = $this->getHttpClient()
            ->withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/api/sms/send-batch', $data);

        if ($response->successful()) {
            $result = $response->json();
            return [
                'status' => $result['success'] ? 'success' : 'error',
                'message' => $result['message'] ?? 'SMS yuborildi',
                'data' => $result['data'] ?? null
            ];
        }

        return [
            'status' => 'error',
            'message' => 'SMS yuborishda xatolik',
            'error' => $response->json()
        ];
    }

    /**
     * Notify parents about absent students
     */
    public function NotifyNotComeStudentParents($students)
    {
        $messages = [];
        $date = date('d.m.Y');

        foreach ($students as $index => $student) {
            $phone = preg_replace('/\D/', '', $student->parents_phone ?? $student->phone ?? '');
            if (empty($phone)) {
                continue;
            }

            // Ensure number starts with country code
            if (!str_starts_with($phone, '998')) {
                $phone = '998' . ltrim($phone, '0');
            }

            $message = "Farzandingiz {$student->name} {$date} sanasida maktabga kelmadi. Ideal Study NTM";

            $messages[] = [
                "user_sms_id" => "sms" . ($index + 1),
                "to" => $phone,
                "text" => $message,
            ];
        }

        if (empty($messages)) {
            return [
                'success' => false,
                'message' => 'Yuborish uchun xabar topilmadi'
            ];
        }

        $data = [
            "messages" => $messages,
            "from" => "4546",
            "dispatch_id" => time()
        ];

        $response = $this->getHttpClient()
            ->withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/api/sms/send-batch', $data);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'success' => false,
            'message' => 'SMS yuborishda xatolik',
            'error' => $response->json()
        ];
    }

    /**
     * Get SMS status
     */
    public function getSmsStatus($smsId)
    {
        $response = $this->getHttpClient()
            ->withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/api/sms/status/' . $smsId);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'success' => false,
            'message' => 'Status olishda xatolik',
            'error' => $response->json()
        ];
    }

    /**
     * Get token info
     */
    public function getTokenInfo()
    {
        $response = $this->getHttpClient()
            ->withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/api/sms/token-info');

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'success' => false,
            'message' => 'Token ma\'lumotlarini olishda xatolik',
            'error' => $response->json()
        ];
    }

    /**
     * Send SMS via API (for backward compatibility)
     */
    public function apiSMS($number, $code)
    {
        $message = "MyTok dasturida tasdiqlash kodi: {$code}";
        return $this->sendStudent($number, $message);
    }
}
