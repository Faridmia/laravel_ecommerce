<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        //
        Paginator::useBootstrap();

        if (\Illuminate\Support\Facades\Schema::hasTable('smtp_settings')) {
            $smtp = \App\Models\SmtpSetting::getSingle();
            if ($smtp) {
                config([
                    'mail.default' => $smtp->mail_mailer,
                    'mail.mailers.smtp.transport' => $smtp->mail_mailer,
                    'mail.mailers.smtp.host' => $smtp->mail_host,
                    'mail.mailers.smtp.port' => $smtp->mail_port,
                    'mail.mailers.smtp.encryption' => $smtp->mail_encryption,
                    'mail.mailers.smtp.username' => $smtp->mail_username,
                    'mail.mailers.smtp.password' => $smtp->mail_password,
                    'mail.from.address' => $smtp->mail_from_address,
                    'mail.from.name' => $smtp->name,
                ]);
            }
        }

        if (!app()->runningInConsole()) {
            $systemSettings = \App\Models\SystemSetting::getSingle();
            view()->share('systemSettings', $systemSettings);

            $homeSetting = \App\Models\HomeSetting::getSingle();
            view()->share('homeSetting', $homeSetting);

            // Bind notifications to admin layouts header
            view()->composer('admin.layouts.header', function ($view) {
                if (auth()->check()) {
                    $notifications = \App\Models\NotificationModel::where('user_id', auth()->id())
                        ->orderBy('id', 'desc')
                        ->limit(10)
                        ->get();
                    $unreadCount = \App\Models\NotificationModel::where('user_id', auth()->id())
                        ->where('is_read', 0)
                        ->count();
                    $view->with([
                        'notifications' => $notifications,
                        'unreadCount' => $unreadCount,
                    ]);
                }
            });
        }
    }
}
