@if(isset($genres))
    <form action="{{ route('genres.update', [$genres->id]) }}" method="POST">
        <input type="hidden" name="_method" value="PUT" />
@else
    <form action="{{ route('genres.store') }}" method="POST">
@endif
{!! csrf_field() !!}
<table class="table">
<input type="hidden" name="id" value="{{ isset($genres) ? $genres->id : '' }}">
<tr>
  <td>{{ __('name') }}</td>
  <td><input name="name" value="{{ isset($genres) ? $genres->name : '' }}"></td>
</tr>
</table>
<input type="submit" value="{{ __('Save') }}" />
</form>