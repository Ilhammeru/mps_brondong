<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserImage;
use App\Models\UserNetwork;
use App\Models\Bonus;
use App\Models\BonusLog;
use App\Models\Prospect;
use App\Models\Serial;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('menuActive')) {
    function menuActive($routeName)
    {
        $class = 'active';
        
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (!function_exists('sendResponse')) {
    function sendResponse($data, $message = 'SUCCESS', $status = 201) {
        return response()->json([
            'data' => $data,
            'message' => $message
        ], $status);
    }
}

if (! function_exists('menuShow')) {
    function menuShow($routeName)
    {
        $class = 'show';
        
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (!function_exists('getIpInfo')) {

    function getIpInfo()
    {
        $ip = $_SERVER["REMOTE_ADDR"];

        //Deep detect ip
        if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);

        $country = @$xml->geoplugin_countryName;
        $city = @$xml->geoplugin_city;
        $area = @$xml->geoplugin_areaCode;
        $code = @$xml->geoplugin_countryCode;
        $long = @$xml->geoplugin_longitude;
        $lat = @$xml->geoplugin_latitude;

        $data['country'] = $country;
        $data['city'] = $city;
        $data['area'] = $area;
        $data['code'] = $code;
        $data['long'] = $long;
        $data['lat'] = $lat;
        $data['ip'] = request()->ip();
        $data['time'] = date('d-m-Y h:i:s A');

        return $data;
    }
}

if (!function_exists('getOsBrowser')) {

    function getOsBrowser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $osPlatform = "Unknown OS Platform";
        $osArray = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );
        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
            }
        }
        $browser = "Unknown Browser";
        $browserArray = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        );
        foreach ($browserArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $browser = $value;
            }
        }

        $data['os_platform'] = $osPlatform;
        $data['browser'] = $browser;

        return $data;
    }
}

if (!function_exists('sendEmail')) {

    function sendEmail($data)
    {
        $subjects = [
            'register' => 'Pendaftaran Berhasil',
            'prospect_register' => 'Pendaftaran Calon Member',
            'prospect_registered' => 'Proses Pendaftaran Telah Selesai',
            'otp' => 'Kode OTP Verifikasi',
            'new_prospect' => 'Prospect Baru',
            'paid_prospect_to_sponsor' => 'Aktivasi Prospek',
            'paid_prospect_to_prospect' => 'Aktivasi Akun',
            'custom_email' => $data['custom_subject'] ?? "",
            'confirm-leave-office' => 'Izin Karyawan'
        ];

        $config = [
            // 'name' => $setting->where('name', 'email_name')->first()->value,
            'name' => 'MPS Brondong, KUD MINATANI',
            // 'email' => $setting->where('name', 'email_address')->first()->value,
            'email' => 'no-reply@mpsbrondong.com',
            // 'host' => $setting->where('name', 'email_host')->first()->value,
            'host' => 'smtp.gmail.com',
            // 'port' => $setting->where('name', 'email_port')->first()->value,
            'port' => 587,
            // 'username' => $setting->where('name', 'email_username')->first()->value,
            'username' => 'gumilang.dev@gmail.com',
            // 'password' => $setting->where('name', 'email_password')->first()->value,
            'password' => 'bkimlzkrndljfznm',
            // 'encryption' => $setting->where('name', 'email_encryption')->first()->value,
            'encryption' => 'tls'
        ];

        $mail = new PHPMailer(true);
        $html = isset($data['message']) ? $data['message'] : view('email_template.'.$data['service'], $data['content'])->render();

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['username'];
            $mail->Password   = $config['password'];
            if ($config['encryption'] == 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }else{
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port       = $config['port'];
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom($config['email'], $config['name']);
            $mail->addAddress($data['receiver'], $data['receiver_name']);
            $mail->addReplyTo($config['email'], $config['name']);
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subjects[$data['subject']];
            $mail->Body    = $html;
            $mail->send();
        } catch ( \Throwable $e) {
            return $e->getMessage();
        }

        return true;
    }
}