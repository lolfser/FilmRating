@if(isset($filmstatus))
    <form action="{{ route('filmstatus.update', [$filmstatus->id]) }}" method="POST">
        <input type="hidden" name="_method" value="PUT" />
@else
    <form action="{{ route('filmstatus.store') }}" method="POST">
@endif
{!! csrf_field() !!}
<table class="table">
<input type="hidden" name="id" value="{{ isset($filmstatus) ? $filmstatus->id : '' }}">
<tr>
  <td>{{ __('name') }}</td>
  <td><input name="name" value="{{ isset($filmstatus) ? $filmstatus->name : '' }}"></td>
</tr>
</table>
<input type="submit" value="{{ __('Save') }}" />
</form>