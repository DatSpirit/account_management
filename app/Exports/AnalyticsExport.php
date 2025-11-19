<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnalyticsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $userId;
    protected $dateRange;

    public function __construct($userId, $dateRange = 30)
    {
        $this->userId = $userId;
        $this->dateRange = $dateRange;
    }

    public function collection()
    {
        return Transaction::where('user_id', $this->userId)
            ->where('created_at', '>=', now()->subDays($this->dateRange))
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Order Code',
            'Product Name',
            'Amount (VND)',
            'Status',
            'Payment Method',
            'Description',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->created_at->format('Y-m-d H:i:s'),
            $transaction->order_code,
            $transaction->product->name ?? 'N/A',
            number_format($transaction->amount, 0, ',', '.'),
            ucfirst($transaction->status),
            $transaction->payment_method ?? 'N/A',
            $transaction->description ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}