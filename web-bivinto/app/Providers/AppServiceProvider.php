<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Guards\JwtGuard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::extend('jwt', function ($app, $name, array $config) {
            return new JwtGuard(Auth::createUserProvider($config['provider']), $app->make('request'));
        });

        try {
            // Lấy tất cả cấu hình email từ bảng settings
            $emailConfigs = \App\Models\Setting::where('group', 'email_config')->pluck('value', 'key');
            
            if ($emailConfigs->isNotEmpty()) {
                config([
                    'mail.mailers.smtp.host' => $emailConfigs->get('mail_host', config('mail.mailers.smtp.host')),
                    'mail.mailers.smtp.port' => $emailConfigs->get('mail_port', config('mail.mailers.smtp.port')),
                    'mail.mailers.smtp.username' => $emailConfigs->get('mail_username', config('mail.mailers.smtp.username')),
                    'mail.mailers.smtp.password' => $emailConfigs->get('mail_password', config('mail.mailers.smtp.password')),
                    'mail.mailers.smtp.encryption' => $emailConfigs->get('mail_encryption', config('mail.mailers.smtp.encryption')),
                    'mail.from.address' => $emailConfigs->get('mail_from_address', config('mail.from.address')),
                    'mail.from.name' => $emailConfigs->get('mail_from_name', config('mail.from.name')),
                ]);
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi trong trường hợp chạy migration mà bảng chưa tồn tại
        }
    }
}