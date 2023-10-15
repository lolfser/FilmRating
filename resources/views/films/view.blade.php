<table class="table table-bordered table-hover">
<tr>
  <td>{{ __('id') }}</td>
  <td>{{ $films->id }}</td>
</tr>
<tr>
  <td>{{ __('name') }}</td>
  <td>{{ $films->name }}</td>
</tr>
<tr>
  <td>{{ __('description') }}</td>
  <td>{{ $films->description }}</td>
</tr>
<tr>
  <td>{{ __('sources_id') }}</td>
  <td>{{ $films->sources_id }}</td>
</tr>
<tr>
  <td>{{ __('film_nr') }}</td>
  <td>{{ $films->film_nr }}</td>
</tr>
<tr>
  <td>{{ __('year') }}</td>
  <td>{{ $films->year }}</td>
</tr>
<tr>
  <td>{{ __('duration') }}</td>
  <td>{{ $films->duration }}</td>
</tr>
<tr>
  <td>{{ __('audio_lang') }}</td>
  <td>{{ $films->audio_lang }}</td>
</tr>
<tr>
  <td>{{ __('subtitle_lang') }}</td>
  <td>{{ $films->subtitle_lang }}</td>
</tr>
<tr>
  <td>{{ __('filmstatus_id') }}</td>
  <td>{{ $films->filmstatus_id }}</td>
</tr>
</table>
