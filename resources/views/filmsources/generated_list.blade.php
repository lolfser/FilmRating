<table class="table">
<tr>
  <td>{{ __('id') }}</td>
  <td>{{ __('name') }}</td>
  <td></td>
</tr>
@foreach($filmsources as $f)
  <tr>
  <td>{{ $f->id }}</td>
  <td>{{ $f->name }}</td>
  <td>
    @can('filmsources_show')
      <a role="button" href="{{ route('filmsources.show', $f->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    @endcan
    @can('filmsources_edit')
      <a role="button" href="{{ route('filmsources.edit', $f->id) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
    @endcan
  </td>
  </tr>
@endforeach
</table>