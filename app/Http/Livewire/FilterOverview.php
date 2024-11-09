<?php

namespace App\Http\Livewire;

use App\Models\Customers;
use App\Services\TrackingService;
use Livewire\Component;

class FilterOverview extends Component
{
    public $customer_id;
    public $filter_date_datatable;

    public function fitler_data(): array
    {

        $date = split_date($this->filter_date_datatable);
        if (!empty($date[0])) {
            $from = $date[0];
            $to = $date[1];
        }
        $customer_id = $this->customer_id;
        $customer = Customers::where('phone', $customer_id)->first('customer_id');
        if (!empty($customer)) {
            $customer_id = $customer->customer_id;
        }
        if (!empty($customer_id) && $date) {
            $service = new TrackingService();
            $data = $service->get_detail_customers($customer_id, $from??null,$to??null, 1, 0);
            if (!empty($data->detail->histories)) {
                $overview = [
                    'count_data' => $data->detail->row_count
                ];
            }
        }
        return $overview ?? [];
    }

    public function render()
    {
        $filteredData = $this->fitler_data();
        $this->emit('overview', $filteredData);
        return view('livewire.filter-overview');
    }
}
