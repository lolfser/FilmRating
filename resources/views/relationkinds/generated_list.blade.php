<table class="table">
<tr>
  <td>{{ __('id') }}</td>
  <td>{{ __('name') }}</td>
  <td></td>
</tr>
@foreach($relationkinds as $r)
  <tr>
  <td>{{ $r->id }}</td>
  <td>{{ $r->name }}</td>
  <td>
    @can('relationkinds_show')
      <a role="button" href="{{ route('relationkinds.show', $r->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    @endcan
    @can('relationkinds_edit')
      <a role="button" href="{{ route('relationkinds.edit', $r->id) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
    @endcan
  </td>
  </tr>
@endforeach
</table>