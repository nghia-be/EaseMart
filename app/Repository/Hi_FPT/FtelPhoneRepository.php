<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\FtelPhoneInterface;
use App\Http\Traits\DataTrait;
use App\Models\Employees;
use App\Models\FtelPhone;
use App\Services\HrService;
use App\Services\ImportPhoneService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class FtelPhoneRepository implements FtelPhoneInterface
{
    use DataTrait;

    /**
     * @throws GuzzleException
     */
    public function all($dataTable, $params)
    {
        return $dataTable->render('ftel-phone.index');
    }

    public function create($dataTable, $params)
    {
        $arrPhone = array_map('trim', explode(',', $params->phone)); // input
        if (!empty($params->input('action_data'))) {
            $data = $this->getFromApi($arrPhone);
        }

        if (!empty($params->input('action_db'))) {
            $data = $this->getFromDataBase($arrPhone);
        }
        return $dataTable->with(['data' => $data ?? []])->render('ftel-phone.create');
    }

    public function update($params, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $model = Employees::find($id)->update($params->except(['_token']));
            return back()->with(['success' => 'Update thành công', 'html' => 'Update thành công']);
        } catch (\Exception $e) {
            return back()->with(['error' => 'Lỗi hệ thống', 'html' => $e->getMessage()]);
        }
    }

    public function getFromApi(array $arrPhone): array
    {
        $data = [];
        $hrService = new HrService();
        $token = $hrService->loginHr()->authorization;
        $dataAPI = array_chunk(array_unique($arrPhone), 50); // [data input api] + [data > 7day] => call api
        foreach ($dataAPI as $value) {
            $dataExport = collect($hrService->getListInfoEmployee($value, $token));
            foreach ($dataExport as $data_value) {
                $data[] = $data_value;
            }
        }
        return json_decode(json_encode($data), true);
    }

    public function getFromDataBase(array $arrPhone)
    {
        $employee = Employees::whereIn('phone', $arrPhone)->get()->unique()->toArray();
        return array_map(fn($tag) => [
            'code' => $tag['employee_code'],
            'fullName' => $tag['full_name'],
            'phoneNumber' => $tag['phone'],
            'emailAddress' => $tag['emailAddress'],
            'organizationCode' => $tag['organizationCode'],
            'organizationCodePath' => $tag['organizationCodePath'],
            'organizationNamePath' => $tag['organizationNamePath'],
        ], $employee );
    }
}
