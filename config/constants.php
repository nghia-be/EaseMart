<?php
const ADMIN = 1;
const LIMIT_PHONE = 100000;
const EMAIL_FTEL_PHONE = 'nghiatd12@fpt.com';
$listRedisKey = [
    'MODULE_BY_ROLE_ID' => 'acl_role_module', // acl_role_moduleX , X là role_id
    'SETTINGS' => 'settings',
    'LIST_CHECKLIST_ID' =>'list_check_list_id',
    'CHART_DOANH_THU_BAO_HIEM_HDI' => 'chart_doanh_thu_bao_hiem_hdi_key',
    'ACCESS_TOKEN_SMS_WORLD' =>'access_token_sms_world',
];
return [
    'ClEAR_LOG_OPTIONS' => [
        0, 15, 30, 60
    ],
    'REDIS_KEY' => $listRedisKey,
];
