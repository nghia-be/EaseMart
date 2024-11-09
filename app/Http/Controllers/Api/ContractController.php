<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contracts;
use App\Models\Customers;
use App\Services\HrService;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $data = [];
        $customerId = $request->customer_id;
        if (empty($customerId)) {
            return printJson([], buildStatusObject('INVALID_INPUT'), 'vi');
        }

        $customer = Customers::select('phone', 'gender', 'birthday')->findOrFail($customerId)->toArray();
        $locations = Contracts::select('location', 'location_id', 'location_code', 'location_name', 'location_zone', 'branch_code', 'branch_name')
            ->whereIn('contract_id', function ($query) use ($customerId) {
                $query->select('contract_id')
                    ->from('customer_contract')
                    ->where('customer_id', $customerId);
            })
            ->get()->toArray();

        $data['personal_info'] = [
            'gender' => $customer['gender'],
            'birthday' => $customer['birthday'],
            'is_employee' => 0,
            'has_contract' => !empty($locations) ? 1 : 0,
        ];
        $hrService = new HrService();
        $token = $hrService->loginHr()->authorization;
        $check_employee = $hrService->getListInfoEmployee([$customer['phone']], $token);
        if (!empty($check_employee)) {
            $data['personal_info']['is_employee'] = 1;
        }

        $data['locations'] = $locations ?? [];

        return printJson($data, buildStatusObject('HTTP_OK'), 'vi');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
