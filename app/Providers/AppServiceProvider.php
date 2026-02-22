<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Produk;
use App\Models\StockCheck;
use Carbon\Carbon;
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
        View::composer('*', function ($view) {

            // Default aman
            $criticalStock = 0;
            $warningStock  = 0;
            $totalLowStock = 0;
            $monitoringStatus = 'aman';
            $sisaMonitoring = 0;
            $riwayatBermasalah = 0;

            try {

                // STOK RENDAH
                $lowStockData = Produk::selectRaw("
                    SUM(CASE WHEN stok < 2 THEN 1 ELSE 0 END) as critical,
                    SUM(CASE WHEN stok BETWEEN 2 AND 4 THEN 1 ELSE 0 END) as warning
                ")->first();

                $criticalStock = (int) ($lowStockData->critical ?? 0);
                $warningStock  = (int) ($lowStockData->warning ?? 0);
                $totalLowStock = $criticalStock + $warningStock;

                // MONITORING HARI INI
                $today = Carbon::today();
                $totalProduk = Produk::count();
                $inputHariIni = StockCheck::whereDate('tanggal', $today)->count();

                if ($totalProduk > 0) {

                    if ($inputHariIni == 0) {
                        $monitoringStatus = 'belum_input';
                        $sisaMonitoring = $totalProduk;

                    } elseif ($inputHariIni < $totalProduk) {
                        $monitoringStatus = 'belum_selesai';
                        $sisaMonitoring = $totalProduk - $inputHariIni;
                    }
                }

            } catch (\Throwable $e) {
                // biarkan default value jika ada error
            }

            $view->with([
                'criticalStock' => $criticalStock,
                'warningStock' => $warningStock,
                'totalLowStock' => $totalLowStock,
                'monitoringStatus' => $monitoringStatus,
                'sisaMonitoring' => $sisaMonitoring,
                'riwayatBermasalah' => $riwayatBermasalah,
            ]);
        });
    }
}