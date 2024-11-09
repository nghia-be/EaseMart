<?php

namespace App\Services;

class SendMailService
{
    /*
        $keyListEmail set in config/listEmail
        $nameService is name title mail
        $mailContent is rendering in view
        $attach is link file to attach
    */
    public function send(string $nameService, string $mailContent, array $email, $attach='')
    {
        // Try to send the email
        try {
            // Construct the URL for the email API
            $url = config('configDomain.DOMAIN_MAIL_CRON.' . env('APP_ENV') . '.domain') . config('configDomain.DOMAIN_MAIL_CRON.' . env('APP_ENV') . '.sub_url');

            // Prepare the email recipient, CC, and BCC fields
            $to = filter_var(preg_replace('/\s+/','', $email['to']));
            $cc = filter_var(preg_replace('/\s+/','', $email['cc']));
            $bcc = filter_var(preg_replace('/\s+/','', $email['bcc']));

            // Construct the request parameters
            $params = [
                "FromEmail"         => "HiFPTsupport@fpt.com.vn", // The sender's email address
                "Recipients"        => $to, // The email recipients
                'CarbonCopys'       => $cc, // The CC recipients
                'BlindCarbonCopys'  => $bcc, // The BCC recipients
                'Subject'           => $nameService, // The email subject
                'Body'              => $mailContent, // The email body
                'AttachUrl'         => $attach // The attachment (if any)
            ];
            // Send the request and return the result
            return sendRequest($url, $params);
        }
            // Catch any exceptions that might be thrown
        catch (\Exception $e) {
            // Print the exception message
            print_r($e);
        }
    }
}
