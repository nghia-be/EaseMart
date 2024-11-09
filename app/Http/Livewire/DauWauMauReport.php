<?php

namespace App\Http\Livewire;

use Livewire\Component;
use \App\Models\DAU_Report;

class DauWauMauReport extends Component
{
    public $showDiv = false;
    public string $selectedDate = '';
    public $selectedLimit = 10;
    public $selectedDuration = 0;
    public $selectedType = [];
    public $selectedZones = [];

    public $dataset = [];
    public $lables = [];
    public $zones = [];
    public $total = [];

    protected $listeners = [
        'date-selected' => 'filteringChart',
    ];

    public function render()
    {
        return view('livewire.dau-wau-mau-report');
    }

    public function filteringChart($selectedDate, $selectedZones = ['Ftel', 'Quest'], $selectedType = 'DAU') {
        $data = $this->readDatabase($selectedDate, $selectedZones, $selectedType);
        $this->emit('updateChart', [
            'datasets'      => $data['dataset'],
            'labels'        => $data['labels'],
            'report_date'   => $selectedDate,
            // 'total'         => $data['total']
            // 'chart'     => $chart
        ]);
    }

    public function readDatabase($selectedDate, $selectedZones, $selectedType) {
        try {
            $total = [
                'DAU'   => 0,
                'WAU'   => 0,
                'MAU'   => 0
            ];
            $date_range = explode(' - ', $selectedDate);
            $query = DAU_Report::whereBetween('to_date', $date_range)->where('type', $selectedType)->whereIn('location_zone', $selectedZones)->selectRaw('to_date, location_zone, SUM(count_login) AS count_login');
            // if(!empty($selectedZones)) {
            //     $query->whereIn('location_zone', $selectedZones);
            // }
            // if(!empty($selectedType)) {
            //     $query->where('type', $selectedType);
            // }
            $data = $query->groupBy(['to_date', 'location_zone'])->orderBy('to_date')->orderBy('location_zone')->get()->toArray();
            $list_zone = array_values(array_unique(array_column($data, 'location_zone')));
            $list_default_value_zone = array_fill_keys($list_zone, 0);
            foreach ($data as $key => $value) {
                if(empty($color[$value['location_zone']])) {
                    $color[$value['location_zone']] = rand_color();
                }

                if(empty($dataset[$value['location_zone']])) {
                    $dataset[$value['location_zone']] = [
                        'label'             => $value['location_zone'],
                        'backgroundColor'   => $color[$value['location_zone']],
                        'borderColor'       => $color[$value['location_zone']],
                        'borderWidth'       => 2,
                        'pointRadius'       => 3,
                        'data'              => []
                    ];
                    
                }
                $dataset[$value['location_zone']]['data'][] = $value['count_login'];
                // $total[$value['to_date']] += $value['count_login'];
            }
            // dd($dataset);
            return ['dataset' => array_values($dataset), 'labels' => array_values(array_unique(array_column($data, 'to_date')))];
        } catch (\Exception $e) {
            return redirect()->with('danger', $e->getMessage());
        }
    }

    public function mount() {
        $zones = DAU_Report::where('location_zone', '!=', '')->select('location_zone')->groupBy(['location_zone'])->orderBy('location_zone')->get()->toArray();
        $selectedDate = date('Y-m-d', strtotime('today midnight')) . ' - ' . date('Y-m-d', strtotime('today midnight'));
        $data = $this->readDatabase($selectedDate, ['Quest', 'Ftel'], 'DAU');
        // dd($data);
        $total = DAU_Report::whereIn('location_zone', ['Quest', 'Ftel'])->where('to_date', date('Y-m-d', strtotime('today midnight')))->selectRaw('type, SUM(count_login) AS count_login')->groupBy(['type'])->orderBy('type')->get()->toArray();
        $this->dataset = $data['dataset'];
        $this->labels = $data['labels'];
        $this->zones = array_column($zones, 'location_zone');
        $this->total = array_column($total, 'count_login', 'type');
    }
}
