<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Filter extends Component
{
    public $showDiv = true;
    public string $selectedDate = '';
    public $selectedLimit = 10;
    public $selectedDuration = 0;
    public string $selectedType = 'line';
    public string $selectedChart = '';

    public function showDiv()
    {
        if ($this->selectedChart == 'MSD' || $this->selectedChart == 'DSD') {
            $this->showDiv = true;
        } else {
            $this->showDiv = false;
        }
    }

    public function render()
    {
        $this->emit('date-selected', $this->selectedChart, $this->selectedDate, $this->selectedType, $this->selectedLimit, $this->selectedDuration);
        return view('livewire.filter');
    }
}
