<?php

namespace App\Providers;

use App\Contract\Hi_FPT\PopupPrivateInterface;
use App\Contract\Hi_FPT\SettingInterface;
use App\Contract\Hi_FPT\StatisticInterface;
use App\Contract\Hi_FPT\TrackingInterface;
use App\Models\Settings;
use App\Repository\Hi_FPT\PopupPrivateRepository;

use App\Contract\Hi_FPT\PopupManageInterface;
use App\Repository\Hi_FPT\PopupManageRepository;

use App\Contract\Hi_FPT\BannerManageInterface;
use App\Repository\Hi_FPT\BannerManageRepository;

use App\Contract\Hi_FPT\ResetPasswordWrongInterface;
use App\Repository\Hi_FPT\ResetPasswordWrongRepository;

use App\Contract\Hi_FPT\FtelPhoneInterface;
use App\Repository\Hi_FPT\FtelPhoneRepository;

use App\Contract\Hi_FPT\SectionLogInterface;
use App\Repository\Hi_FPT\SectionLogRepository;

use App\Contract\Hi_FPT\DeeplinkInterface;
use App\Repository\Hi_FPT\DeeplinkRepository;

use App\Contract\Hi_FPT\ScreenInterface;
use App\Repository\Hi_FPT\ScreenRepository;

use App\Contract\Hi_FPT\BehaviorInterface;
use App\Repository\Hi_FPT\BehaviorRepository;

use App\Contract\Hi_FPT\GetPhoneNumberInterface;
use App\Repository\Hi_FPT\GetPhoneNumberRepository;

use App\Contract\Hi_FPT\RenderDeeplinkInterface;
use App\Repository\Hi_FPT\RenderDeeplinkRepository;

use App\Contract\Hi_FPT\PaymentSupportInterface;
use App\Repository\Hi_FPT\PaymentSupportRepository;

use App\Repository\Hi_FPT\SettingRepository;
use App\Repository\Hi_FPT\StatisticRepository;
use App\Repository\Hi_FPT\TrackingRepository;
use App\Repository\RepositoryAbstract;
use App\Repository\RepositoryInterface;
use Illuminate\Support\ServiceProvider;
// Auto Generate

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //Bind Provider
        $this->app->bind(RepositoryInterface::class, RepositoryAbstract::class);
        $this->app->bind(PopupPrivateInterface::class, PopupPrivateRepository::class);
        $this->app->bind(PopupManageInterface::class, PopupManageRepository::class);
        $this->app->bind(BannerManageInterface::class, BannerManageRepository::class);
        $this->app->bind(ResetPasswordWrongInterface::class, ResetPasswordWrongRepository::class);
        $this->app->bind(FtelPhoneInterface::class, FtelPhoneRepository::class);
        $this->app->bind(SectionLogInterface::class, SectionLogRepository::class);
        $this->app->bind(DeeplinkInterface::class, DeeplinkRepository::class);
        $this->app->bind(ScreenInterface::class, ScreenRepository::class);
        $this->app->bind(BehaviorInterface::class, BehaviorRepository::class);
        $this->app->bind(GetPhoneNumberInterface::class, GetPhoneNumberRepository::class);
        $this->app->bind(RenderDeeplinkInterface::class, RenderDeeplinkRepository::class);
        $this->app->bind(PaymentSupportInterface::class, PaymentSupportRepository::class);
        $this->app->bind(SettingInterface::class, SettingRepository::class);
        $this->app->bind(StatisticInterface::class, StatisticRepository::class);
        $this->app->bind(TrackingInterface::class, TrackingRepository::class);
        $this->app->bind(SettingInterface::class, function () {
            return new SettingRepository(new Settings());
        });
    }

}
