<table class="table">
<tr>
  <td>{{ __('id') }}</td>
  <td>{{ __('name') }}</td>
  <td></td>
</tr>
@foreach($filmmodifications as $f)
  <tr>
  <td>{{ $f->id }}</td>
  <td>{{ $f->name }}</td>
  <td>
    @can('filmmodifications_show')
      <a role="button" href="{{ route('filmmodifications.show', $f->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    @endcan
    @can('filmmodifications_edit')
      <a role="button" href="{{ route('filmmodifications.edit', $f->id) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
    @endcan
  </td>
  </tr>
@endforeach
</table>