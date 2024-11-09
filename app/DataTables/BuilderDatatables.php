<?php

namespace App\DataTables;

use App\Repository\RepositoryInterface;
use Closure;
use Collective\Html\HtmlFacade as Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

abstract class BuilderDatatables extends DataTable
{
    /**
     * @var bool
     */
    protected $bStateSave = true;

    /**
     * @var DataTables
     */
    protected $table;

    protected $ajaxUrl;

    /**
     * @var int
     */
    protected $pageLength = 10;


    protected $orderBy = 1;

    /**
     * @var string
     */
    protected $view = 'table.table';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var bool
     */
    protected $hasCheckbox = true;

    /**
     * @var bool
     */
    protected $hasActions = false;

    /**
     * @var string
     */
    protected $bulkChangeUrl = '';

    /**
     * @var bool
     */
    protected $hasFilter = false;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var bool
     */
    protected $useDefaultSorting = true;

    /**
     * @var int
     */
    protected $defaultSortColumn = 1;

    /**
     * TableAbstract constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(Datatables $table)
    {
        $this->table = $table;
        $this->pageLength = -1;
        $this->bulkChangeUrl = '';
    }

    /**
     * @param string $key
     * @return string
     */
    public function getOption(string $key): ?string
    {
        return Arr::get($this->options, $key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setOption(string $key, $value): self
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return HtmlBuilder
     *
     * @since 2.1
     */


    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax($this->getAjax())
            ->orderBy($this->orderBy, 'desc')
            ->parameters([
                'scroll' => false,
                'searching' => true,
                'searchDelay' => 350,
                'bDestroy' => true,
                'autoWidth' => false,
                'serverSide' => true,
                'destroy' => true,
                'retrieve' => false,
                'initComplete' => $this->htmlInitComplete(),
                'drawCallback' => $this->htmlDrawCallback(),
                'dom' => $this->getDom(),
                'buttons' => $this->getBuilderParameters(),
                'columnDefs' => [
                    ['searchable' => false,
                        'targets' => 'notsearchable'],
                    [
                        'sortable' => false,
                        'targets' => 'notsortable'
                    ],
                ],
            ])
            ->language([
                'emptyTable' => preg_replace("/{(\d+)}/", ('dt_entries'), ('Không có dữ liệu')),
                'info' => preg_replace("/{(\d+)}/", ('dt_entries'), 'Hiển thị trang _PAGE_ của _PAGES_ trang'),
                'infoFiltered' => preg_replace("/{(\d+)}/", ('dt_entries'), ('dt_info_filtered')),
                'lengthMenu' => '_MENU_',
                'loadingRecords' => ('Đang tải'),
                'processing' => '<div class="dt-loader"></div>',
                'search' => '<div class="input-group"><span class="input-group-addon"><span class="fa fa-search"></span></span>',
                'searchPlaceholder' => ('Tìm kiếm'),
                'zeroRecords' => '<p class="text-center">Không có dữ liệu</p>',
                'paginate' => [
                    'first' => 'Trang đầu',
                    'last' => 'Trang cuối',
                    'next' => ('Tiếp'),
                    'previous' => ('Trước'),
                ],
                'aria' => [
                    'sortAscending' => (''),
                    'sortDescending' => ('Sắp sếp giảm dần'),
                ],
            ]);
    }

    /**
     * @return array
     * @since 2.1
     */
    public function getColumns(): array
    {
        $columns = $this->columns();
        foreach ($columns as $key => &$column) {
            $column['class'] = Arr::get($column, 'class') . ' column-key-' . $key;
        }

        if ($this->hasCheckbox) {
            $columns = array_merge($this->getCheckboxColumnHeading(), $columns);
        }


        return $columns;
    }

    public function getCheckboxColumnHeading()
    {
        return [
            'checkbox' => [
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
                'width' => '10px',
                'class' => 'text-center',
                'title' => '<div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="responsive"><label></label></div>',
            ]
        ];
    }

    protected function addCreateButton(string $url, array $buttons = []): array
    {
        $buttons['create'] = [
            'link' => $url,
            'title' => 'Tạo mới',
        ];
        return $buttons;
    }

    protected function getFunction($callback)
    {
        if (is_string($callback)) {
            if (strpos($callback, '@')) {
                $callback = explode('@', $callback);

                return [app('\\' . $callback[0]), $callback[1]];
            }

            return $callback;
        } elseif ($callback instanceof Closure) {
            return $callback;
        } elseif (is_array($callback)) {
            return $callback;
        }
        return false;
    }

    /**
     * @return array
     * @since 2.1
     */
    abstract public function columns();

    public function getAjax()
    {
        if ($this->ajaxUrl) {
            return $this->ajaxUrl;
        }
        return '';
    }

    /**
     * @param array $ajaxUrl
     * @return $this
     */
    public function setAjax(array $ajaxUrl): self
    {
        $this->ajaxUrl = $ajaxUrl;

        return $this;
    }

    /**
     * @return null|string
     */
    protected function getDom(): ?string
    {
        return "<'row'><'row'<'col-md-7'lB><'col-md-5'f>>rt<'row'<'col-md-4'i><'col-md-8 dataTables_paging'<'#colvis'><'.dt-page-jump'>p>>";
    }

    /**
     * @return array
     * @since 2.1
     */
    public function getBuilderParameters(): array
    {
        $params = [
            'stateSave' => true,
        ];

        $buttons = array_merge($this->getButtons(), $this->getDefaultButtons());
        if (!$buttons) {
            return $params;
        }
        return $params + compact('buttons');
    }

    /**
     * @return array
     * @since 2.1
     */
    public function getButtons(): array
    {
        $buttons = $this->buttons();
        if (!$buttons) {
            return [];
        }

        $data = [];

        foreach ($buttons as $key => $button) {
            if (Arr::get($button, 'extend') == 'collection') {
                $data[] = $button;
            } else {
                $data[] = [
                    'className' => 'btn btn-default btn-sm btn-default-dt-options',
                    'text' => Html::tag('span', $button['text'], [
                        'data-action' => $key,
                        'data-href' => Arr::get($button, 'link'),
                    ])->toHtml(),
                ];
            }
        }

        return $data;
    }

    /**
     * @return array
     * @since 2.1
     */
    public function buttons(){}

    /**
     * @return array
     */
    public function getActionsButton(): array
    {
        if (!$this->getActions()) {
            return [];
        }

        return [
            [
                'extend' => 'collection',
                'text' => '<span>' . trans('core/base::forms.actions') . ' <span class="caret"></span></span>',
                'buttons' => $this->getActions(),
            ],
        ];
    }

    /**
     * @return array
     * @since 2.1
     */
    public function getActions(): array
    {
        if (!$this->actions()) {
            return [];
        }

        $actions = [];

        foreach ($this->actions() as $key => $action) {
            $actions[] = [
                'className' => 'action-item',
                'text' => '<span data-action="' . $key . '" data-href="' . $action['link'] . '"> ' . $action['text'] . '</span>',
            ];
        }

        return $actions;
    }

    /**
     * @return array
     * @since 2.1
     */
    public function actions()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getDefaultButtons(): array
    {
        return [
            [
                'extend' => 'collection',
                'text' => 'Xuất ra',
                'autoClose' => true,
                'attr' => [
                    'class' => 'btn btn-default buttons-collection btn-sm btn-default-dt-options'
                ],
                'buttons' => [
                    [
                        'text' => 'Excel',
                        'extend' => 'excel'
                    ],
                    [
                        'text' => 'CSV',
                        'extend' => 'csv'
                    ],
                    [
                        'text' => 'PDF',
                        'extend' => 'pdf'
                    ],[
                        'text' => 'Print',
                        'extend' => 'print'
                    ]
                ]
            ],
            [
                'extend' => 'colvis',
                'text' => '<i class="fa fa-cog"></i>',
                'autoClose' => true,
                'attr' => [
                    'class' => 'btn btn-default btn-sm btn-default-dt-options btn-dt-colvis'
                ],
            ],
            
            [
                'extend' => 'collection',
                'text' => '<i class="fa fa-refresh"></i>',
                'autoClose' => true,
                'attr' => [
                    'class' => 'btn btn-default btn-sm btn-default-dt-options btn-dt-reload'
                ],
                'action'    => 'function ( e, dt, node, config ) {dt.ajax.reload();}',
            ]
        ];
    }

    /**
     * @return string
     */
    public function htmlInitComplete(): ?string
    {
        return 'function () {' . $this->htmlInitCompleteFunction(). $this->htmlInitCompleteFunctionDefault() . '}';
    }

    /**
     * @return string
     */
    public function htmlInitCompleteFunction(){}

    public function htmlInitCompleteFunctionDefault(): ?string
    {
        return "
            this.wrap(`<div class='table-responsive'></div>`);
            this.parents('.table-loading').removeClass('table-loading');
            this.removeClass('dt-table-loading');
            var btnReload = $('.btn-dt-reload');
            btnReload.attr('data-toggle', 'tooltip');
            btnReload.attr('title', 'Tải lại');

            if (is_mobile() && $(window).width() < 400  && t.find('tbody td:first-child input[type=`checkbox`]').length > 0) {
                t.DataTable().column(0).visible(false, false).columns.adjust();
                $('a[data-target*=`bulk_actions`]').addClass('hide');
              }
        ";
    }

    /**
     * @return string
     */
    public function htmlDrawCallback(): ?string
    {
        return 'function () {' . $this->htmlDrawCallbackFunction() . '}';
    }

    /**
     * @return string
     */
    public function htmlDrawCallbackFunction(): ?string
    {
        return '

        ';
    }

    public function toJson($data, array $escapeColumn = [], bool $mDataSupport = true)
    {
//        if ($this->repository && $this->repository->getModel()) {
//            $data = apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel());
//        }

        return $data
            ->escapeColumns($escapeColumn)
            ->make($mDataSupport);
    }
}
