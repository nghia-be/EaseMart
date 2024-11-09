<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LocationController extends Controller
{
    public function getCurrentLocation(Request $request)
    {
        try {
            // Lấy địa chỉ IP của người dùng
            $ip = $request->ip();

            // Kiểm tra xem token có được thiết lập không
            $token = env('IPINFO_TOKEN');
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token IPINFO không được thiết lập.',
                ], 500);
            }

            // Gọi API từ dịch vụ ipinfo.io để lấy vị trí dựa trên IP
            $client = new Client();
            $response = $client->get("https://ipinfo.io/{$ip}/json?token=" . $token);

            // Chuyển đổi dữ liệu JSON thành mảng
            $data = json_decode($response->getBody(), true);

            // Lấy tọa độ (latitude, longitude)
            $location = $data['loc'] ?? '';
            $coordinates = explode(',', $location);
            $latitude = $coordinates[0] ?? '';
            $longitude = $coordinates[1] ?? '';

            // Nếu có tọa độ, gọi Google Maps Geocoding API để lấy địa chỉ chi tiết
            
            $geocodeResponse = $client->get("https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key=AIzaSyD0ulwyD-c8W2n8J4d9as2nbOxz-VuFZsc");

            $geocodeData = json_decode($geocodeResponse->getBody(), true);

            // Tìm kiếm các địa chỉ chi tiết từ kết quả geocoding
            $addressComponents = $geocodeData['results'][0]['address_components'] ?? [];

            $province = '';
            $district = '';
            $ward = '';

            foreach ($addressComponents as $component) {
                if (in_array('administrative_area_level_1', $component['types'])) {
                    $province = $component['long_name'];
                }
                if (in_array('administrative_area_level_2', $component['types'])) {
                    $district = $component['long_name'];
                }
                if (in_array('sublocality_level_1', $component['types'])) {
                    $ward = $component['long_name'];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'ip' => $data['ip'] ?? '',
                    'hostname' => $data['hostname'] ?? '',
                    'city' => $data['city'] ?? '',
                    'region' => $data['region'] ?? '',
                    'country' => $data['country'] ?? '',
                    'location' => $data['loc'] ?? '',
                    'province' => $province,
                    'district' => $district,
                    'ward' => $ward,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy vị trí hiện tại.',
            ], 500);
        }
    }
}
