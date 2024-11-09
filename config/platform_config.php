<?php
/**
 * Created by PhpStorm.
 * User: phucnguyen
 * Date: 2021-11-03
 * Time: 09:29
 */

/**
 * Updated by PhpStorm.
 * User: trinhdev
 * Date: 2022-06-16
 * Time: 09:29
 */
return [
    'current_theme' => 'themes',
    'background' =>[
        'url' => '/images/tet.png',
        'color' =>'#dc354529',
        'position'  => 'bottom',
        'repeat'    => 'repeat-x',
        'size'      => 'inhernit'
    ],
//  config cho popup
    'type_popup_service' => [
        'popup_custom_image_transparent' => 'Center box có button',
        'popup_image_transparent' => 'Center box không có button',
        'popup_full_screen' => 'Full screen có button',
        'popup_image_full_screen' => 'Full screen không có button'
    ],
    'repeatTime' => [
        'once_time' => 'Chỉ hiển thị 1 lần, khi người dùng tắt hoặc xem chi tiết rồi thì không hiện nữa',
        'always' => 'Luôn luôn hiển thị khi mở app trong thời gian còn hiệu lực',
//        'other' => 'Chỉ hiển thị 1 lần trong thời gian N phút, quá N phút khi người dùng mở app thì mới hiển thị lại'
    ],
//  config cho banner và popup chung
    'object_type' => [
        'topic' => 'Nhóm được đăng ký sẵn',
        'location ' => 'Vùng miền',
        'contract_phone ' => 'Số điện thoại',
        'contract_no ' => 'Số hợp đồng',
        'hifpt_id' => 'Id của user Hi FPT'
    ],
    'object'    => [
        'all' => 'Tất cả KH cài Hi FPT (bao gồm guest)',
        'fpt_customer' => 'Tất cả KH có dùng dịch vụ (không bao gồm guest)',
        'guest' => 'Tất cả KH không dùng dịch vụ (guest)'
    ],
    'air_direction_key'    => [
        'open_url_in_app_with_access_token' => 'Mở URL trong ứng dụng bằng mã thông báo truy cập',
        'go_to_screen' => 'Đi tới màn hình chỉ định',
        'open_url_in_app' => 'Mở URL trong webkit app',
        'open_url_out_app' => 'Mở URL bằng trình duyệt của thiết bị'
    ]
];
