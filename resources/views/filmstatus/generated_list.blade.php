<table class="table">
<tr>
  <td>{{ __('id') }}</td>
  <td>{{ __('name') }}</td>
  <td></td>
</tr>
@foreach($filmstatus as $f)
  <tr>
  <td>{{ $f->id }}</td>
  <td>{{ $f->name }}</td>
  <td>
    @can('filmstatus_show')
      <a role="button" href="{{ route('filmstatus.show', $f->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    @endcan
    @can('filmstatus_edit')
      <a role="button" href="{{ route('filmstatus.edit', $f->id) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
    @endcan
  </td>
  </tr>
@endforeach
</table>