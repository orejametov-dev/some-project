<?php

namespace App\Services\SMS;

use App\Exceptions\BusinessException;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class OtpProtector
{
    private $key;
    private $cached_info;
    const EXPIRES_IN = 600; // 10 min

    public function __construct($key)
    {
        $this->key = $key;
        $this->cached_info = Cache::tags(CacheService::OTP)->get($this->key);
    }

    public function writeOtpToCache($otp)
    {
        // если первый раз отправили - сохраняем в кеш и ставим expires in
        // если второй раз отправили - сохраняем код но не меняем expires in
        $ttl = self::EXPIRES_IN;

        if (empty($this->cached_info['otps'])) {
            $generated_time = Carbon::now();
            Cache::tags(CacheService::OTP)->put($this->key, [
                'otps' => [$otp],
                'generated_time' => $generated_time->toDateTimeString()
            ], $ttl);
        } else {
            $otps = $this->cached_info['otps'];
            $this->verifyRequestOtpCount();
            $generated_time = Carbon::parse($this->cached_info['generated_time']);
            $now = Carbon::now();
            $past_seconds = $now->diffInSeconds($generated_time);

            $ttl = self::EXPIRES_IN - $past_seconds;
            $merged_codes = array_merge([$otp], $otps);
            Cache::tags(CacheService::OTP)->put($this->key, [
                'otps' => $merged_codes,
                'generated_time' => $generated_time->toDateTimeString()
            ], $ttl);
        }
    }

    public function verifyOtp($otp, $forget = true)
    {
        $this->cached_info = Cache::tags(CacheService::OTP)->get($this->key);
        if (!$this->cached_info) {
            throw new BusinessException('СМС код не был отправлен', 'otp_not_sent', [
                'ru' => 'СМС код не был отправлен',
                'uz' => ' SMS kodi yuborilmadi'
            ],400);
        }

        if (!in_array($otp, $this->cached_info['otps'])) {
            throw new BusinessException('Неверный код подтверждения', 'wrong_otp', [
                'ru' => 'Неверный код подтверждения',
                'uz' => 'Tasdiqlash kodi noto\'g\'ri'
            ] ,400);
        }

        if ($forget) {
            Cache::tags(CacheService::OTP)->forget($this->key);
        }
    }

    public function verifyRequestOtpCount()
    {
        if (!empty($this->cached_info['otps']) and count($this->cached_info['otps']) >= 3) {
            throw new BusinessException('Лимит исчерпан', 'limit_exceeded', [
                'ru' => 'Лимит исчерпан',
                'uz' => ' limit tugadi'
            ] , 400);
        }
    }
}

