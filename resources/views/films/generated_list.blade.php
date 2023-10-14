<table class="table">
<tr>
  <td>{{ __('id') }}</td>
  <td>{{ __('name') }}</td>
  <td>{{ __('description') }}</td>
  <td>{{ __('sources_id') }}</td>
  <td>{{ __('film_nr') }}</td>
  <td>{{ __('year') }}</td>
  <td>{{ __('duration') }}</td>
  <td>{{ __('audio_lang') }}</td>
  <td>{{ __('subtitle_lang') }}</td>
  <td>{{ __('filmstatus_id') }}</td>
  <td>{{ __('created') }}</td>
  <td>{{ __('updated') }}</td>
  <td></td>
</tr>
@foreach($films as $f)
  <tr>
  <td>{{ $f->id }}</td>
  <td>{{ $f->name }}</td>
  <td>{{ $f->description }}</td>
  <td>{{ $f->sources_id }}</td>
  <td>{{ $f->film_nr }}</td>
  <td>{{ $f->year }}</td>
  <td>{{ $f->duration }}</td>
  <td>{{ $f->audio_lang }}</td>
  <td>{{ $f->subtitle_lang }}</td>
  <td>{{ $f->filmstatus_id }}</td>
  <td>{{ $f->created }}</td>
  <td>{{ $f->updated }}</td>
  <td>
    @can('films_show')
      <a role="button" href="{{ route('films.show', $f->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    @endcan
    @can('films_edit')
      <a role="button" href="{{ route('films.edit', $f->id) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
    @endcan
  </td>
  </tr>
@endforeach
</table>