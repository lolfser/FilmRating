@if(isset($triggerkinds))
    <form action="{{ route('triggerkinds.update', [$triggerkinds->id]) }}" method="POST">
        <input type="hidden" name="_method" value="PUT" />
@else
    <form action="{{ route('triggerkinds.store') }}" method="POST">
@endif
{!! csrf_field() !!}
<table class="table">
<input type="hidden" name="id" value="{{ isset($triggerkinds) ? $triggerkinds->id : '' }}">
<tr>
  <td>{{ __('name') }}</td>
  <td><input name="name" value="{{ isset($triggerkinds) ? $triggerkinds->name : '' }}"></td>
</tr>
</table>
<input type="submit" value="{{ __('Save') }}" />
</form>