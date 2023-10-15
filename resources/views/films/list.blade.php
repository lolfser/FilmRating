<div>
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <h1 class="mt-8 text-2xl font-medium text-gray-900">
            Liste aller Filme
        </h1>
    </div>

    <table class="table">
    <tr>
      <td>{{ __('attributes.id') }}</td>
      <td>{{ __('attributes.name') }}</td>
      <td>{{ __('attributes.description') }}</td>
      <td>{{ __('attributes.sources_id') }}</td>
      <td>{{ __('attributes.film_nr') }}</td>
      <td>{{ __('attributes.year') }}</td>
      <td>{{ __('attributes.duration') }}</td>
      <td>{{ __('attributes.audio_lang') }}</td>
      <td>{{ __('attributes.subtitle_lang') }}</td>
      <td>{{ __('attributes.filmstatus_id') }}</td>
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
    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
        <div>
            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                <a href="/dashboard" class="inline-flex items-center font-semibold text-indigo-700">
                    Zurück zum Dashboard
                </a>
            </p>
        </div>
    </div>
</div>
