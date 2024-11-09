@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper">
        <div class="content">
            <form action="" id="article">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <!-- begin::title -->
                        <div class="tw-flex tw-justify-between tw-mb-2">
                            <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                                <span class="tw-text-lg">Thêm</span>
                            </h4>
                        </div>
                        <!-- end::title -->
                        <div class="panel_s">
                            <div class="panel-body">
                                <div class="alert alert-warning">
                                    Mô tả: <a
                                        href="#">Checkout</a></div>
                                <div class="row">
                                    <!-- begin::tab -->
                                    <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                                        <div class="horizontal-tabs">
                                            <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#tab_staff_profile" aria-controls="tab_staff_profile"
                                                       role="tab"
                                                       data-toggle="tab">
                                                        Tiểu sử </a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#staff_permissions" aria-controls="staff_permissions"
                                                       role="tab"
                                                       data-toggle="tab">
                                                        Quyền hạn </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- end::tab -->
                                    <!-- begin::tabContent -->
                                    <div class="tab-content tw-mt-5">
                                        <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                                            <div class="tw-bg-neutral-50 tw-rounded-md tw-p-6 tw-border tw-border-solid tw-border-neutral-200 tw-mb-4">
                                                <div class="form-group" app-field-wrapper="subject">
                                                    <label for="subject" class="control-label">
                                                        <i class="fa-regular fa-circle-question" data-toggle="tooltip"
                                                           data-title="Useful if you want to include additional information on the subscription invoice, e.q. what this subscription includes."
                                                           data-original-title="" title=""></i>
                                                        Chủ đề
                                                    </label>
                                                    <input type="text" id="subject" name="subject" class="form-control"
                                                           autofocus="1" value="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="rel_type"
                                                           class="control-label">Liên quan</label>
                                                    <select name="rel_type" id="rel_type" class="selectpicker"
                                                            data-width="100%"
                                                            data-none-selected-text="Không có mục nào được chọn">
                                                        <option value=""></option>
                                                        <option value="lead">Mục tiêu</option>
                                                        <option value="customer">Khách hàng</option>
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" app-field-wrapper="date"><label for="date"
                                                                                                                class="control-label">Ngày</label>
                                                            <div class="input-group date"><input type="text" id="date"
                                                                                                 name="date"
                                                                                                 class="form-control datepicker"
                                                                                                 value="2023-02-14"
                                                                                                 autocomplete="off">
                                                                <div class="input-group-addon">
                                                                    <i class="fa-regular fa-calendar calendar-icon"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group" app-field-wrapper="open_till"><label
                                                                for="open_till" class="control-label">Ngày chốt</label>
                                                            <div class="input-group date"><input type="text" id="open_till"
                                                                                                 name="open_till"
                                                                                                 class="form-control datepicker"
                                                                                                 value="2023-02-21"
                                                                                                 autocomplete="off">
                                                                <div class="input-group-addon">
                                                                    <i class="fa-regular fa-calendar calendar-icon"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group no-mbot">
                                                <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i>
                                                    Các thẻ</label>
                                                <input type="text" class="tagsinput" id="tags" name="tags"
                                                       value=""
                                                       data-role="tagsinput">
                                            </div>
                                            <div class="form-group">
                                                <div class="radio radio-primary radio-inline">
                                                    <input type="radio" value="1" id="sq_1" name="show_quantity_as"
                                                           data-text="Số lượng" checked>
                                                    <label for="sq_1">SLượng</label>
                                                </div>
                                                <div class="radio radio-primary radio-inline">
                                                    <input type="radio" value="2" id="sq_2" name="show_quantity_as"
                                                           data-text="Giờ" >
                                                    <label for="sq_2">Giờ</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="profile_image"
                                                       class="profile-image">Ảnh đại diện</label>
                                                <input type="file" name="profile_image" class="form-control"
                                                       id="profile_image">
                                            </div>
                                            <div class="form-group" app-field-wrapper="email_signature">
                                                <label for="email_signature" class="control-label">Chữ kí email</label>
                                                <textarea id="email_signature" name="email_signature"
                                                          class="form-control" data-entities-encode="true"
                                                          rows="4"></textarea>
                                            </div>
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" id="staff_article" name="staff_article">
                                                <label for="staff_article">Hiện</label>
                                            </div>
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" id="disabled" name="disabled">
                                                <label for="disabled">Ẩn</label>
                                            </div>
                                            <p class="bold">Mô tả</p>
                                            <div class="form-group" app-field-wrapper="description">
                                            <textarea id="description"
                                                      name="description"
                                                      class="form-control tinymce tinymce-manual"
                                                      rows="4"></textarea>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="staff_permissions">Content Tab 2</div>
                                    </div>
                                    <div
                                        class="btn-bottom-toolbar bottom-transaction text-right sm:tw-flex sm:tw-items-center sm:tw-justify-between">
                                        <p class="no-mbot pull-left mtop5 btn-toolbar-notice tw-hidden sm:tw-block">
                                            <b>Description</b>
                                        </p>
                                        <div>
                                            <button type="button"
                                                    class="btn btn-default mleft10 proposal-form-submit save-and-send transaction-submit">
                                                Save and send
                                            </button>
                                            <button
                                                class="btn btn-primary mleft5 proposal-form-submit transaction-submit"
                                                type="button"> Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(function() {
            validate_invoice_form();
            // Init accountacy currency symbol
            init_currency();
            // Project ajax search
            init_ajax_project_search_by_customer_id();
            // Maybe items ajax search
            init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
        });
    </script>
@endpush
