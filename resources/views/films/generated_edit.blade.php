@if(isset($films))
    <form action="{{ route('films.update', [$films->id]) }}" method="POST">
        <input type="hidden" name="_method" value="PUT" />
@else
    <form action="{{ route('films.store') }}" method="POST">
@endif
{!! csrf_field() !!}
<table class="table">
<input type="hidden" name="id" value="{{ isset($films) ? $films->id : '' }}">
<tr>
  <td>{{ __('name') }}</td>
  <td><input name="name" value="{{ isset($films) ? $films->name : '' }}"></td>
</tr>
<tr>
  <td>{{ __('description') }}</td>
  <td><input name="description" value="{{ isset($films) ? $films->description : '' }}"></td>
</tr>
<tr>
  <td>{{ __('sources_id') }}</td>
  <td><input type="number" name="sources_id" value="{{ isset($films) ? $films->sources_id : '' }}"></td>
</tr>
<tr>
  <td>{{ __('film_nr') }}</td>
  <td><input type="number" name="film_nr" value="{{ isset($films) ? $films->film_nr : '' }}"></td>
</tr>
<tr>
  <td>{{ __('year') }}</td>
  <td><input type="number" name="year" value="{{ isset($films) ? $films->year : '' }}"></td>
</tr>
<tr>
  <td>{{ __('duration') }}</td>
  <td><input type="number" name="duration" value="{{ isset($films) ? $films->duration : '' }}"></td>
</tr>
<tr>
  <td>{{ __('audio_lang') }}</td>
  <td><input name="audio_lang" value="{{ isset($films) ? $films->audio_lang : '' }}"></td>
</tr>
<tr>
  <td>{{ __('subtitle_lang') }}</td>
  <td><input name="subtitle_lang" value="{{ isset($films) ? $films->subtitle_lang : '' }}"></td>
</tr>
<tr>
  <td>{{ __('filmstatus_id') }}</td>
  <td><input name="filmstatus_id" value="{{ isset($films) ? $films->filmstatus_id : '' }}"></td>
</tr>
<tr>
  <td>{{ __('created') }}</td>
  <td><input name="created" value="{{ isset($films) ? $films->created : '' }}"></td>
</tr>
<tr>
  <td>{{ __('updated') }}</td>
  <td><input name="updated" value="{{ isset($films) ? $films->updated : '' }}"></td>
</tr>
</table>
<input type="submit" value="{{ __('Save') }}" />
</form>