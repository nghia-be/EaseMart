<?php
$domainReportConfig = [
    'local'             => [
        'URL'           => 'http://hi-report-stag.fpt.vn/',
        'VERSION'       => 'v10',
        'CLIENT_KEY'    => 'hifpt_report',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
    'staging'           => [
        'URL'           => 'http://hi-report-stag.fpt.vn/',
        'VERSION'       => 'v10',
        'CLIENT_KEY'    => 'hifpt_report',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
    'production'        => [
        'URL'           => 'http://hi-report.fpt.vn/',
        'VERSION'       => 'v10',
        'CLIENT_KEY'    => 'hifpt_report',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
];

$domainInsideConfig = [
    'local'             => [
        'URL'           => 'http://hi-inside.fpt.vn/',
        'VERSION'       => 'v1',
        'CLIENT_KEY'    => 'hifpt_inside',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
    'staging'           => [
        'URL'           => 'http://hi-inside.fpt.vn/',
        'VERSION'       => 'v1',
        'CLIENT_KEY'    => 'hifpt_inside',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
    'production'        => [
        'URL'           => 'http://hi-inside.fpt.vn/',
        'VERSION'       => 'v1',
        'CLIENT_KEY'    => 'hifpt_inside',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
];

$domainCustomerConfig = [
    'local'             => [
        'URL'           => 'http://hi-customer-stag.fpt.vn/',
        'CLIENT_KEY'    => 'hifpt_customer_local',
        'SUB_DOMAIN'    => ['hi-customer-local', 'swagger'],
        'SECRET_KEY'    => 'xxxxxxhifpt2018',
        'CLIENT_KEY_AIR_DIRECTION'   => 'toolcmsbwrue784wr239ffkdkduq',
        'SECRET_KEY_AIR_DIRECTION'    => 'toolcmsxxx2022',
    ],
    'staging'           => [
        'URL'           => 'http://hi-customer-stag.fpt.vn/',
        'CLIENT_KEY'    => 'hifpt_customer_local',
        'SUB_DOMAIN'    => ['hi-customer-local', 'swagger'],
        'SECRET_KEY'    => 'xxxxxxhifpt2018',
        'CLIENT_KEY_AIR_DIRECTION'   => 'toolcmsbwrue784wr239ffkdkduq',
        'SECRET_KEY_AIR_DIRECTION'    => 'toolcmsxxx2022',
    ],
    'production'        => [
        'URL'           => 'http://hi-customer.fpt.vn/',
        // 'URL'           => 'http://hi-customer-stag.fpt.vn/',
        'CLIENT_KEY'    => 'hifpt_customer_local',
        'SUB_DOMAIN'    => ['hi-customer-local', 'swagger'],
        'SECRET_KEY'    => 'xxxxxxhifpt2018',
        'CLIENT_KEY_AIR_DIRECTION'   => 'toolcmsbwrue784wr239ffkdkduq',
        'SECRET_KEY_AIR_DIRECTION'    => 'toolcmsxxx2022',
    ],
];

$domainSmsWorld = [
    'local'             => [
        'URL'           => 'http://smsworld.fpt.vn/',
        'PREFIX'       => 'api',
    ],
    'staging'           => [
        'URL'           => 'http://smsworld.fpt.vn/',
        'PREFIX'       => 'api',
    ],
    'production'        => [
        'URL'           => 'http://smsworld.fpt.vn/',
        'PREFIX'       => 'api',
    ],
];

$domainNewsEventConfig = [
    'local'             => [
        'URL'           => 'hi-news-event-stag.fpt.vn/',
        'CLIENT_KEY'    => '9895ee2f7616a73ab8be47e5df5a8924',
        'SECRET_KEY'    => 'e063d2833da02c8dac4cac106b825535',
    ],
    'staging'           => [
        'URL'           => 'hi-news-event-stag.fpt.vn/',
        'CLIENT_KEY'    => '9895ee2f7616a73ab8be47e5df5a8924',
        'SECRET_KEY'    => 'e063d2833da02c8dac4cac106b825535',
    ],
    'production'        => [
        'URL'           => 'hi-news-event.fpt.vn/',
        'CLIENT_KEY'    => '9895ee2f7616a73ab8be47e5df5a8924',
        'SECRET_KEY'    => 'e063d2833da02c8dac4cac106b825535',
    ],
];

$domainModemInfo = [
    'local'             => [
        'URL'           => 'http://hi-modem-stag.fpt.vn/',
        'CLIENT_KEY'    => 'hifpt_modem',
        'SECRET_KEY'    => 'xxxxxxhifpt2018',
        'SUB_DOMAIN'    => ['provider', 'admin-tool'],
    ],
    'staging'           => [
        'URL'           => 'http://hi-modem-stag.fpt.vn/',
        'CLIENT_KEY'    => 'hifpt_modem',
        'SECRET_KEY'    => 'xxxxxxhifpt2018',
        'SUB_DOMAIN'    => ['provider', 'admin-tool'],
    ],
    'production'        => [
        'URL'           => 'http://hi-modem.fpt.vn/',
        'CLIENT_KEY'    => 'hifpt_modem',
        'SECRET_KEY'    => 'xxxxxxhifpt2018',
        'SUB_DOMAIN'    => ['provider', 'admin-tool'],
    ],
];

$domainModemAccessPoint = [
    'local'             => [
        'URL'           => 'http://staging-hi-api.fpt.vn/',
        'SUB_DOMAIN'    => ['v68'],
    ],
    'staging'           => [
        'URL'           => 'http://staging-hi-api.fpt.vn/',
        'SUB_DOMAIN'    => ['v68'],
    ],
    'production'        => [
        'URL'           => 'http://hi-api.fpt.vn/',
        'SUB_DOMAIN'    => ['v68'],
    ],
];

$domainModemContractInfo = [
    'local'             => [
        'URL'           => 'http://hi-inside.fpt.vn/',
        'SUB_DOMAIN'    => ['v1'],
        'CLIENT_KEY'    => 'hifpt_inside',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
    'staging'           => [
        'URL'           => 'http://hi-inside.fpt.vn/',
        'SUB_DOMAIN'    => ['v1'],
        'CLIENT_KEY'    => 'hifpt_inside',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
    'production'        => [
        'URL'           => 'http://hi-inside.fpt.vn/',
        'SUB_DOMAIN'    => ['v1'],
        'CLIENT_KEY'    => 'hifpt_inside',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
];

$domainIconManagement = [
    'local'             => [
        'URL'           => 'http://hi-customer-stag.fpt.vn/',
        'SUB_DOMAIN'    => ['hi-customer-local', 'tool'],
        'CLIENT_KEY'    => 'hifpt_customer_local',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
    'staging'           => [
        'URL'           => 'http://hi-customer-stag.fpt.vn/',
        'SUB_DOMAIN'    => ['hi-customer-local', 'tool'],
        'CLIENT_KEY'    => 'hifpt_customer_local',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
    'production'        => [
        'URL'           => 'http://hi-customer-stag.fpt.vn/',
        'SUB_DOMAIN'    => ['hi-customer-local', 'tool'],
        'CLIENT_KEY'    => 'hifpt_customer_local',
        'SECRET_KEY'    => 'xxxxxxhifpt2018'
    ],
];

$domainHr = [
    'local'             => [
        'URL'           => 'http://hrapi.fpt.vn/',
        'USERNAME'      => 'hifpt@hr.fpt.vn',
        'PASSWORD'      => '!@#hiFPT123'
    ],
    'staging'           => [
        'URL'           => 'http://hrapi.fpt.vn/',
        'USERNAME'      => 'hifpt@hr.fpt.vn',
        'PASSWORD'      => '!@#hiFPT123'
    ],
    'production'        => [
        'URL'           => 'http://hrapi.fpt.vn/',
        'USERNAME'      => 'hifpt@hr.fpt.vn',
        'PASSWORD'      => '!@#hiFPT123'
    ],
];

$domainMail = [
    'local'             => [
        'URL'           => 'http://systemmailapi.fpt.vn/',
        'SUB_DOMAIN'    => ['api', 'SendMailSMTP']
    ],
    'staging'           => [
        'URL'           => 'http://systemmailapi.fpt.vn/',
        'SUB_DOMAIN'    => ['api', 'SendMailSMTP']
    ],
    'production'        => [
        'URL'           => 'http://systemmailapi.fpt.vn/',
        'SUB_DOMAIN'    => ['api', 'SendMailSMTP']
    ],
];

$domainUploadIcon = [
    'local'             => [
        'URL'           => 'https://staging-hi-static.fpt.vn/',
        'CLIENT_KEY'    => 'hifpt_static_CMS',
        'SECRET_KEY'    => 'xxxxxxhifpt2022'
    ],
    'staging'           => [
        'URL'           => 'https://staging-hi-static.fpt.vn/',
        'CLIENT_KEY'    => 'hifpt_static_CMS',
        'SECRET_KEY'    => 'xxxxxxhifpt2022'
    ],
    'production'        => [
        'URL'           => 'https://staging-hi-static.fpt.vn/',
        // 'URL'           => 'https://hi-static.fpt.vn/upload_CMS.php/',
        'CLIENT_KEY'    => 'hifpt_static_CMS',
        'SECRET_KEY'    => 'xxxxxxhifpt2022'
    ],
];

$domainAuth = [
    'local'             => [
        'URL'           => 'http://hi-authapi-stag.fpt.vn/',
        'CLIENT_KEY'    => 'HiFPTProvider',
        'SECRET_KEY'    => 'dwudjsoakpiudjfkspalskdjao'
    ],
    'staging'           => [
        'URL'           => 'http://hi-authapi-stag.fpt.vn/',
        'CLIENT_KEY'    => 'HiFPTProvider',
        'SECRET_KEY'    => 'dwudjsoakpiudjfkspalskdjao'
    ],
    'production'        => [
        'URL'           => 'http://hi-authapi.fpt.vn/',
        'CLIENT_KEY'    => 'HiFPTProvider',
        'SECRET_KEY'    => 'dwudjsoakpiudjfkspalskdjao'
    ],
];

$domainPayment = [
    'local'             => [
        'URL'           => 'http://hi-payment-stag.fpt.vn/',
        'CLIENT_KEY'    => 'HiFPTProvider',
        'SECRET_KEY'    => 'dwudjsoakpiudjfkspalskdjao'
    ],
    'staging'           => [
        'URL'           => 'http://hi-payment-stag.fpt.vn/',
        'CLIENT_KEY'    => 'HiFPTProvider',
        'SECRET_KEY'    => 'dwudjsoakpiudjfkspalskdjao'
    ],
    'production'        => [
        'URL'           => 'http://hi-payment.fpt.vn/',
        'CLIENT_KEY'    => 'HiFPTProvider',
        'SECRET_KEY'    => 'dwudjsoakpiudjfkspalskdjao'
    ],
];

$domainAPI = [
    'local'             => [
        'URL'           => 'http://hifpt-api-stag.fpt.vn/',
        'CLIENT_KEY'    => 'CMSHiFPT',
        'SECRET_KEY'    => 'e5c372e6e0cac0ee79f6eedbf7d05323'
    ],
    'staging'           => [
        'URL'           => 'http://hifpt-api-stag.fpt.vn/',
        'CLIENT_KEY'    => 'CMSHiFPT',
        'SECRET_KEY'    => 'e5c372e6e0cac0ee79f6eedbf7d05323'
    ],
    'production'        => [
        'URL'           => 'http://hifpt-api.fpt.vn/',
        'CLIENT_KEY'    => 'CMSHiFPT',
        'SECRET_KEY'    => 'e5c372e6e0cac0ee79f6eedbf7d05323'
    ],
];

$domainHiFPT = [
    'local'             => [
        'URL'           => 'https://hi.fpt.vn/',
        'CLIENT_KEY'    => '',
        'SECRET_KEY'    => ''
    ],
    'staging'           => [
        'URL'           => 'https://staging-hi.fpt.vn/',
        'CLIENT_KEY'    => '',
        'SECRET_KEY'    => ''
    ],
    'production'        => [
        'URL'           => 'https://hi.fpt.vn/',
        'CLIENT_KEY'    => '',
        'SECRET_KEY'    => ''
    ],
];

$domainTracking = [
    'local'             => [
        'URL'           => 'https://tracking-stg.fconnect.com.vn/',
        'CLIENT_KEY'    => '55bbbd24-b198-11ed-89b3-2811a830db8a',
        'SECRET_KEY'    => 'xkfa2dA!SD901ND!@@x91dak'
    ],
    'staging'           => [
        'URL'           => 'https://tracking-stg.fconnect.com.vn/',
        'CLIENT_KEY'    => '55bbbd24-b198-11ed-89b3-2811a830db8a',
        'SECRET_KEY'    => 'xkfa2dA!SD901ND!@@x91dak'
    ],
    'production'        => [
        'URL'           => 'https://analysis.fconnect.com.vn/',
        'CLIENT_KEY'    => '55asdd24-b198-43ed-82b3-2111a830asx8a',
        'SECRET_KEY'    => 'xx!@2dA!lD1331Na!98pr91dak'
    ],
];
$domainMailCron = [
    'local'             => [
        'domain'        => 'http://systemmailapi.fpt.vn',
        'sub_url'       => '/api/SendMailSMTP/InsertInfoSendMailSMTP'
    ],
    'staging'           => [
        'domain'        => 'http://systemmailapi.fpt.vn',
        'sub_url'       => '/api/SendMailSMTP/InsertInfoSendMailSMTP'
    ],
    'production'        => [
        'domain'        => 'http://systemmailapi.fpt.vn',
        'sub_url'       => '/api/SendMailSMTP/InsertInfoSendMailSMTP'
    ],
];


return [
    'DOMAIN_REPORT'                 => $domainReportConfig,
    'DOMAIN_INSIDE'                 => $domainInsideConfig,
    'DOMAIN_CUSTOMER'               => $domainCustomerConfig,
    'DOMAIN_SMS_WORLD'              => $domainSmsWorld,
    'DOMAIN_NEWS_EVENT'             => $domainNewsEventConfig,
    'DOMAIN_MODEM_INFO'             => $domainModemInfo,
    'DOMAIN_MODEM_ACCESS_POINT'     => $domainModemAccessPoint,
    'DOMAIN_MODEM_CONTRACT_INFO'    => $domainModemContractInfo,
    'DOMAIN_ICON_MANAGEMENT'        => $domainIconManagement,
    'DOMAIN_HR'                     => $domainHr,
    'DOMAIN_MAIL'                   => $domainMail,
    'DOMAIN_UPLOAD'                 => $domainUploadIcon,
    'DOMAIN_AUTH'                   => $domainAuth,
    'DOMAIN_PAYMENT'                => $domainPayment,
    'DOMAIN_API'                    => $domainAPI,
    'DOMAIN_HIFPT'                  => $domainHiFPT,
    'DOMAIN_TRACKING'               => $domainTracking,
    'DOMAIN_MAIL_CRON'               => $domainMailCron,
];
