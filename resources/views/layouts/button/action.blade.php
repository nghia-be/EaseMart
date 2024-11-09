<div style="display:flex; justify-content:center">
    <a style="float: left; margin-right: 5px" href="/{{$module}}/edit/{{$row->id}}" class="btn btn-sm fas fa-edit btn-icon bg-olive"></a>
{{-- @if($module =='roles')
    <a style="float: left; margin-right: 5px" href="/aclrole/edit/{{$row->id}}" class="btn btn-sm fas fa-edit btn-icon bg-black"></a>
@endif --}}
    <form action="/{{$module}}/destroy/{{$row->id}}" method="POST" onSubmit="handleSubmit(event,this)">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
    </form>
</div>
