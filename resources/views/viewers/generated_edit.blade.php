@if(isset($viewers))
    <form action="{{ route('viewers.update', [$viewers->id]) }}" method="POST">
        <input type="hidden" name="_method" value="PUT" />
@else
    <form action="{{ route('viewers.store') }}" method="POST">
@endif
{!! csrf_field() !!}
<table class="table">
<tr>
  <td>{{ __('users_id') }}</td>
  <td><input type="number" name="users_id" value="{{ isset($viewers) ? $viewers->users_id : '' }}"></td>
</tr>
<tr>
  <td>{{ __('initials') }}</td>
  <td><input name="initials" value="{{ isset($viewers) ? $viewers->initials : '' }}"></td>
</tr>
<tr>
  <td>{{ __('comment') }}</td>
  <td><input name="comment" value="{{ isset($viewers) ? $viewers->comment : '' }}"></td>
</tr>
</table>
<input type="submit" value="{{ __('Save') }}" />
</form>