<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TransDetail;
use App\Models\FineType;
use Carbon\Carbon;

class SimulateFineCalculation extends Command
{
    protected $signature = 'simulate:fine {tdetailId}';
    protected $description = 'Simulate fine calculation for a specific transaction detail ID';

    public function handle()
    {
        $id = $this->argument('tdetailId');
        $td = TransDetail::with(['book', 'transaction'])->find($id);

        if (!$td) {
            $this->error('TransDetail not found.');
            return;
        }

        $this->info("Return Status: " . $td->td_status);

        $fineType = FineType::where('reason', ucfirst($td->td_status))->first();

        if (!$fineType) {
            $this->warn("No FineType found for reason: " . ucfirst($td->td_status));
            return;
        }

        $this->info("Fine Type Found: " . $fineType->reason);
        $this->info("Is Per Day: " . ($fineType->is_per_day ? 'Yes' : 'No'));
        $this->info("Default Amount: " . $fineType->default_amount);

        $fineAmount = 0;

        if ($fineType->is_per_day) {
            $dueDate = Carbon::parse($td->transaction->due_date);
            $returnDate = now();
            $daysLate = $dueDate->diffInDays($returnDate, false);

            $this->info("Days Late: " . $daysLate);

            if ($daysLate > 0) {
                $fineAmount = $daysLate * $fineType->default_amount;
            }
        } else {
            $fineAmount = $fineType->default_amount;
        }

        $this->info("Final Calculated Fine: ₱" . number_format($fineAmount, 2));
    }
}
