<?php

namespace App\Http\Controllers\Admin;

use App\Contract\Hi_FPT\SettingInterface;
use App\DataTables\Admin\ModuleDataTable;
use App\DataTables\Admin\UrlSettingDataTable;
use App\Http\Controllers\BaseController;
use App\Http\Traits\DataTrait;
use App\Jobs\SendMailManualJob;
use App\Models\Group_Module;
use Illuminate\Http\Request;

use App\Models\Settings;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class GeneralSettingsController extends BaseController
{
    use DataTrait;
    /**
     * @var SettingInterface
     */
    protected $settingRepository;

    /**
     * SettingController constructor.
     * @param SettingInterface $settingRepository
     */
    public function __construct(SettingInterface $settingRepository)
    {
        parent::__construct();
        $this->title = 'Settings';
        $this->settingRepository = $settingRepository;
    }

    public function index(Request $request, UrlSettingDataTable $settingDataTable, ModuleDataTable $moduleDataTable)
    {
        // Get key hi_admin_cron_
        $settings_name = Settings::where('name', 'like', 'hi_admin_cron_'. "%")
            ->where('name', 'like', "%".'_enable')
            ->get()->pluck('name');
        $key = [];
        foreach ($settings_name as $value) {
            $startIndex = strpos($value, 'hi_admin_cron_');
            $service = substr($value, $startIndex);
            $key[] = substr($service, strlen('hi_admin_cron_'), strlen($service) - strlen('hi_admin_cron_') - strlen('_enable'));
        }

        $settings = Settings::where('name', 'not like', 'hi_admin_cron_'. "%")
            ->where('name', 'not like', 'uri_config')
            ->get()->pluck('value', 'name');
        $group = $request->input('group', '');
        switch ($group) {
            case 'general':
                $title = 'Tổng quan';
                $view = 'settings.includes.general';
                $data = [
                    'setting' => $settings
                ];
                break;
            case 'site_url':
                $title = 'Config site URl';
                $view = 'settings.includes.site-url';
                if ($request->ajax() && request()->get('table') == 'detail') {
                    return $settingDataTable->render('setting.includes.site-url');
                }
                $data = [
                    'setting' => $settingDataTable->html(),
                ];
                break;

            case 'modules':
                $title = 'Config modules';
                $view = 'settings.includes.module';
                $list_icon = explode(",", file_get_contents(public_path('fontawsome.txt')));
                $list_group_module = $this->getAll(new Group_Module);
                if ($request->ajax() && request()->get('table') == 'detail') {
                    return $moduleDataTable->render('setting.includes.site-url');
                }
                $data = [
                    'setting' => $moduleDataTable->html(),
                    'list_icon' => $list_icon,
                    'list_group_module' => $list_group_module
                ];
                break;
            case 'cronjob':
                $title = 'Email chu kì/Cron Job';
                $view = 'settings.includes.cronjob';
                $data = [
                    'key' => $key
                ];
                break;
            case 'info':
                $title = 'System/Server Info';
                $view = 'settings.includes.information';
                $data = [];
                break;
            case 'misc':
                $title = 'Cài đặt khác';
                $view = 'settings.includes.misc';
                $data = [];
                break;
            default:
                $title = 'Tổng quan';
                $view = 'settings.includes.general';
                $data = [
                    'setting' => $settings
                ];

        }
        return view('settings.list', compact('title', 'view', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \JsonException
     */

    public function postEdit(Request $request)
    {
        $this->saveSettings($request->except([
            '_token',
            'hi_admin_cron_add_key'
        ]));

        $add_key = $request->input('hi_admin_cron_add_key');
        if ($add_key) {
            $email_example = [
                'to'    => 'nghiatd12@fpt.com',
                'cc'    => 'nghiatd12@fpt.com',
                'bcc'   => ' ',
            ];
            $listEmail[] = array_filter($email_example);
            setting()->set('hi_admin_cron_'.$add_key.'_enable', '0')->save();
            setting()->set('hi_admin_cron_'.$add_key.'_list_email', json_encode($listEmail,JSON_THROW_ON_ERROR))->save();
            setting()->set('hi_admin_cron_'.$add_key.'_time', '* * * * *')->save();
        }
        return redirect()->back()->with(['success'=>'Update thành công', 'html'=>'Update thành công']);
    }

    /**
     * @param array $data
     * @throws \JsonException
     */
    protected function saveSettings(array $data)
    {
        $data = convert_email_setting($data);
        foreach ($data as $settingKey => $settingValue) {
            if (is_array($settingValue)) {
                $settingValue = json_encode(array_filter($settingValue), JSON_THROW_ON_ERROR);
            }
            setting()->set($settingKey, (string)$settingValue);
        }
        setting()->save();
    }

    protected function saveUriSetting(Request $request)
    {
        $request->validate([
            'uri' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9\-]+$/',
            ],
        ]);
        $form = (object)[
          'name' => $request->name_uri,
          'uri' => $request->uri,
          'status' => "1"
        ];
        $model = Settings::where('name', 'uri_config')->get()->pluck('value', 'name');
        $data = json_decode($model['uri_config'], false);
        foreach ($data as $value) {
            if ($value->uri == $request->uri) {
                return response(['status'=>'danger', 'html'=> 'Uri đã tồn tại!']);
            }
        }
        $data[] = $form;
        setting()->set('uri_config', json_encode($data));
        setting()->save();
        return response()->json(['status'=>'success', 'html'=> 'Thêm mới thành công!']);
    }

    protected function sendMailManually(Request $request)
    {
        $request->validate([
            'daterange' => 'required',
        ]);
        $date_ = split_date($request->daterange);
        $date = [$date_[0], $date_[1]];
        Artisan::call('reportAirConditionWeekly', ['date' => $date]);
        return response()->json(['status'=>'success', 'html'=> 'Gửi mail thành công! Vui lòng kiểm tra email']);
    }
}
