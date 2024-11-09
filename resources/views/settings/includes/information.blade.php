<h4 class="tw-my-0 tw-text-lg tw-font-medium">
    <a download="system-info.xls" class="btn btn-default btn-sm tw-mr-2" href="#"
       onclick="return ExcellentExport.excel(this, 'system-info', 'System Info');">
        <i class="fa-regular fa-file-excel"></i>
    </a>
    System/Server Information
</h4>
<div class="table-responsive">
    <table class="table table-bordered" id="system-info">
        <thead>
        <tr>
            <th>Variable Name</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="bold">OS</td>
            <td> <?php echo PHP_OS; ?> </td>
        </tr>
        <tr>
            <td class="bold">Webserver</td>
            <td> <?php echo isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'N/A'; ?> </td>
        </tr>
        <tr>
            <td class="bold">Webserver User</td>
            <td> {{ auth()->user()->email }} </td>
        </tr>
        <tr>
            <td class="bold">Server Protocol</td>
            <td> <?php echo isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'N/A'; ?> </td>
        </tr>
        <tr>
            <td class="bold">PHP Version</td>
            <td> <?php echo PHP_VERSION; ?> </td>
        </tr>
        <tr>
            <td class="bold">PHP Extension "curl"</td>
            <td>
                <?php
                if (!extension_loaded('curl')) {
                    echo "<span class='text-danger'>Not enabled</span>";
                } else {
                    $curlVersion = curl_version();
                    echo "<span class='text-success'>Enabled (Version: " . $curlVersion['version'] . ')</span>';
                }
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">PHP Extension "openssl"</td>
            <td>
                <?php
                if (!extension_loaded('openssl')) {
                    echo "<span class='text-danger'>Not enabled</span>";
                } else {
                    echo "<span class='text-success'>Enabled (Version: " . OPENSSL_VERSION_NUMBER . ')</span>';
                }
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">PHP Extension "mbstring"</td>
            <td>
                <?php
                if (!extension_loaded('mbstring')) {
                    echo "<span class='text-danger'>Not enabled</span>";
                } else {
                    echo "<span class='text-success'>Enabled</span>";
                }
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">PHP Extension "iconv"</td>
            <td>
                <?php
                if (!extension_loaded('iconv') && !function_exists('iconv')) {
                    echo "<span class='text-danger'>Not enabled</span>";
                } else {
                    echo "<span class='text-success'>Enabled</span>";
                }
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">PHP Extension "IMAP"</td>
            <td>
                <?php
                if (!extension_loaded('imap')) {
                    echo "<span class='text-danger'>Not enabled</span>";
                } else {
                    echo "<span class='text-success'>Enabled</span>";
                }
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">PHP Extension "GD"</td>
            <td>
                <?php
                if (!extension_loaded('gd')) {
                    echo "<span class='text-danger'>Not enabled</span>";
                } else {
                    echo "<span class='text-success'>Enabled</span>";
                }
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">PHP Extension "zip"</td>
            <td>
                <?php
                if (!extension_loaded('zip')) {
                    echo "<span class='text-danger'>Not enabled</span>";
                } else {
                    echo "<span class='text-success'>Enabled</span>";
                }
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">MySQL Version</td>
            <td> {{ DB::select("SELECT VERSION() as version")[0]->version }} </td>

        </tr>
        <tr>
            <td class="bold">MySQL Max Allowed Connections</td>
            <td> {{ DB::select("SHOW VARIABLES LIKE 'max_connections'")[0]->Value }}</td>

        </tr>
        <tr>
            <td class="bold">Maximum Packet Size</td>
            <td> {{ bytesToSize('', DB::select("SHOW VARIABLES LIKE 'max_allowed_packet'")[0]->Value) }} </td>

        </tr>
        <tr>
            <td class="bold">sql_mode</td>
            <td> {{ DB::select('SELECT @@sql_mode as mode')[0]->mode }} </td>

        </tr>
        <tr>
            <td class="bold">bcmath</td>
            <td>
                <?php
                echo extension_loaded('bcmath') ? 'Yes' : 'No';
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">max_input_vars</td>
            <td>
                <?php
                $max_input_vars = ini_get('max_input_vars');
                echo $max_input_vars ? $max_input_vars : 'N/A';
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">upload_max_filesize</td>
            <td>
                <?php
                $upload_max_filesize = ini_get('upload_max_filesize');
                echo $upload_max_filesize ? $upload_max_filesize : 'N/A';
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">post_max_size</td>
            <td>
                <?php
                $post_max_size = ini_get('post_max_size');
                echo $post_max_size ? $post_max_size : 'N/A';
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">max_execution_time</td>
            <td>
                <?php
                $execution_time = ini_get('max_execution_time');
                echo $execution_time ? $execution_time : 'N/A';
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">memory_limit</td>
            <td>
                <?php
                $memory = ini_get('memory_limit');
                echo $memory ? $memory : 'N/A';
                if (floatval($memory) < 128 && floatval($memory) > -1) {
                    echo '<br /><span class="text-warning">128M is recommended value (or bigger)</span>';
                }
                ?>
            </td>

        </tr>
        <tr>
            <td class="bold">allow_url_fopen</td>
            <td>
                <?php
                $url_f_open = ini_get('allow_url_fopen');
                if ($url_f_open != '1'
                    && strcasecmp($url_f_open, 'On') != 0
                    && strcasecmp($url_f_open, 'true') != 0
                    && strcasecmp($url_f_open, 'yes') != 0) {
                    echo "<span class='bold'>Allow_url_fopen is not enabled! (Value: $url_f_open)</span>";
                } else {
                    echo "<span class='text-success'>Enabled</span>";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="bold">Environment</td>
            <td>
                <?php
                echo env("APP_ENV");
                ?>
            </td>
        </tr>
        <tr>
            <td class="bold">CSRF Enabled</td>
            <td>{{ !empty(csrf_token()) ? 'enabled' : 'disabled' }}</td>
        </tr>
        <tr>
            <td class="bold">Using platform_helper.php</td>
            <td>
                <?php
                echo file_exists(app_path() . '/helpers/platform_helper.php') ? 'Yes' : 'No';
                ?>
            </td>
        </tr>
        <tr>
            <td class="bold">Using custom.css</td>
            <td>
                <?php
                echo file_exists(base_path() . '/public/assets/css/custom.css') ? 'Yes' : 'No';
                ?>
            </td>
        </tr>
        <tr>
            <td class="bold">Total modules</td>
            <td>
                <?php
                echo DB::table('modules')->count();
                ?>
            </td>
        </tr>
        <tr>
            <td class="bold">Total active modules</td>
            <td>
                <?php
                echo DB::table('modules')->where('status', 1)->count();
                ?>
            </td>
        </tr>
        <tr>
            <td class="bold">Base URL</td>
            <td>
                <?php
                echo url('/');
                ?>
            </td>
        </tr>
        <tr>
            <td class="bold">Files Permissions</td>
            <td>
                <?php
                $permissionsIssues = false;
                if (!is_writable(base_path() . '/app')) {
                    $permissionsIssues = true;
                    echo "<span class='text-danger'>No (Make uploads/estimates writable) - Permissions 0755</span><br />";
                }
                if (!is_writable(base_path() . '/public/assets')) {
                    $permissionsIssues = true;
                    echo "<span class='text-danger'>No (Make uploads/proposals writable) - Permissions 0755</span><br />";
                }
                if (!is_writable(base_path() . '/public/build')) {
                    $permissionsIssues = true;
                    echo "<span class='text-danger'>No (Make uploads/ticket_attachments writable) - Permissions 0755</span><br />";
                }
                if (!is_writable(base_path() . '/public/custom_js')) {
                    $permissionsIssues = true;
                    echo "<span class='text-danger'>No (Make uploads/tasks writable) - Permissions 0755</span><br />";
                }
                if (!is_writable(base_path() . '/public/custom_css')) {
                    $permissionsIssues = true;
                    echo "<span class='text-danger'>No (Make uploads/staff_profile_images writable) - Permissions 0755</span><br />";
                }
                if (!is_writable(base_path() . '/public/vendor')) {
                    $permissionsIssues = true;
                    echo "<span class='text-danger'>No (Make uploads/projects writable) - Permissions 0755</span><br />";
                }
                if (!$permissionsIssues) {
                    echo 'No files permission issues found';
                }
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
@push('script')
    <script src="{{ url('/assets/plugins/excellentexport/excellentexport.min.js') }}"></script>
@endpush
