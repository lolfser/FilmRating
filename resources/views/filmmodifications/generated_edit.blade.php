@if(isset($filmmodifications))
    <form action="{{ route('filmmodifications.update', [$filmmodifications->id]) }}" method="POST">
        <input type="hidden" name="_method" value="PUT" />
@else
    <form action="{{ route('filmmodifications.store') }}" method="POST">
@endif
{!! csrf_field() !!}
<table class="table">
<input type="hidden" name="id" value="{{ isset($filmmodifications) ? $filmmodifications->id : '' }}">
<tr>
  <td>{{ __('name') }}</td>
  <td><input name="name" value="{{ isset($filmmodifications) ? $filmmodifications->name : '' }}"></td>
</tr>
</table>
<input type="submit" value="{{ __('Save') }}" />
</form>