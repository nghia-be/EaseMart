<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label
                for="manually_email_list_email" class="control-label">
                {{ __('Email người nhận khi lấy dữ liệu thủ công') }}
            </label>
            <div class="form-group no-mbot tw-mt-2">
                <label for="email_to" class="control-label"><i class="fa fa-envelope" aria-hidden="true"></i>
                    Email to</label>
                <input type="text" class="tagsinput" id="manually_email_email_to" name="manually_email_email_to"
                       value="{{ json_decode(setting('manually_email_list_email'), true)[0]['to'] }}"
                       data-role="tagsinput">
            </div>
            <div class="form-group no-mbot">
                <label for="email_cc" class="control-label"><i class="fa fa-envelope" aria-hidden="true"></i>
                    Email cc</label>
                <input type="text" class="tagsinput" id="manually_email_email_cc" name="manually_email_email_cc"
                       value="{{ json_decode(setting('manually_email_list_email'), true)[0]['cc'] }}"
                       data-role="tagsinput">
            </div>
            <div class="form-group no-mbot">
                <label for="email_bcc" class="control-label"><i class="fa fa-envelope" aria-hidden="true"></i>
                    Email bcc</label>
                <input type="text" class="tagsinput" id="manually_email_email_bcc" name="manually_email_email_bcc"
                       value="{{ json_decode(setting('manually_email_list_email'), true)[0]['bcc'] }}"
                       data-role="tagsinput">
            </div>
        </div>
        <hr>
    </div>
</div>
