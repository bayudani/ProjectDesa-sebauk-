<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TransactionsChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Transaksi per Bulan';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Ambil jumlah transaksi per bulan (12 bulan terakhir)
        $data = Transaction::selectRaw('MONTH(transaction_date) as month, COUNT(*) as total')
            ->whereYear('transaction_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Bikin array kosong isi 12 bulan (biar rapi dari Jan ke Dec)
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $data->get($i, 0); // kalau gak ada transaksi, kasih 0
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $chartData,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#f59e0b',
                    'fill' => false,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // atau 'bar' kalau mau model batang
    }
}
