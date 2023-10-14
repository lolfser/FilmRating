@if(isset($grades))
    <form action="{{ route('grades.update', [$grades->id]) }}" method="POST">
        <input type="hidden" name="_method" value="PUT" />
@else
    <form action="{{ route('grades.store') }}" method="POST">
@endif
{!! csrf_field() !!}
<table class="table">
<input type="hidden" name="id" value="{{ isset($grades) ? $grades->id : '' }}">
<tr>
  <td>{{ __('value') }}</td>
  <td><input type="number" name="value" value="{{ isset($grades) ? $grades->value : '' }}"></td>
</tr>
<tr>
  <td>{{ __('trend') }}</td>
  <td><input name="trend" value="{{ isset($grades) ? $grades->trend : '' }}"></td>
</tr>
</table>
<input type="submit" value="{{ __('Save') }}" />
</form>