{{--Start Modal Detail--}}
<div id="{{ $id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{!! $title !!}</h4>
            </div>
            <div class="modal-body" id="{{ $id }}">
                @include($form)
            </div>
        </div>
    </div>
</div>
{{--End Modal Detail--}}
