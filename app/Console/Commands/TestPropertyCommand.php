<?php

namespace App\Console\Commands;

use App\Models\Property;
use Illuminate\Console\Command;

class TestPropertyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:properties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test property model and display statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Property Statistics:');
        $this->line('');
        
        $total = Property::count();
        $available = Property::available()->count();
        $featured = Property::featured()->count();
        $rented = Property::where('status', 'rented')->count();
        
        $this->info("Total Properties: {$total}");
        $this->info("Available Properties: {$available}");
        $this->info("Featured Properties: {$featured}");
        $this->info("Rented Properties: {$rented}");
        
        $this->line('');
        $this->info('Featured Properties:');
        
        Property::featured()->get()->each(function ($property) {
            $this->line("- {$property->title} ({$property->type}) - {$property->city} - \${$property->rent_amount}");
        });
        
        $this->line('');
        $this->info('Property Types Distribution:');
        
        $typeStats = Property::select('type')
            ->selectRaw('count(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type')
            ->toArray();
            
        foreach ($typeStats as $type => $count) {
            $this->line("- {$type}: {$count}");
        }
    }
}
