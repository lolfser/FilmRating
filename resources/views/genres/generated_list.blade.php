<table class="table">
<tr>
  <td>{{ __('id') }}</td>
  <td>{{ __('name') }}</td>
  <td></td>
</tr>
@foreach($genres as $g)
  <tr>
  <td>{{ $g->id }}</td>
  <td>{{ $g->name }}</td>
  <td>
    @can('genres_show')
      <a role="button" href="{{ route('genres.show', $g->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    @endcan
    @can('genres_edit')
      <a role="button" href="{{ route('genres.edit', $g->id) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
    @endcan
  </td>
  </tr>
@endforeach
</table>