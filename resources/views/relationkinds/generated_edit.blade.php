@if(isset($relationkinds))
    <form action="{{ route('relationkinds.update', [$relationkinds->id]) }}" method="POST">
        <input type="hidden" name="_method" value="PUT" />
@else
    <form action="{{ route('relationkinds.store') }}" method="POST">
@endif
{!! csrf_field() !!}
<table class="table">
<input type="hidden" name="id" value="{{ isset($relationkinds) ? $relationkinds->id : '' }}">
<tr>
  <td>{{ __('name') }}</td>
  <td><input name="name" value="{{ isset($relationkinds) ? $relationkinds->name : '' }}"></td>
</tr>
</table>
<input type="submit" value="{{ __('Save') }}" />
</form>