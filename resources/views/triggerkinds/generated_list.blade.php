<table class="table">
<tr>
  <td>{{ __('id') }}</td>
  <td>{{ __('name') }}</td>
  <td></td>
</tr>
@foreach($triggerkinds as $t)
  <tr>
  <td>{{ $t->id }}</td>
  <td>{{ $t->name }}</td>
  <td>
    @can('triggerkinds_show')
      <a role="button" href="{{ route('triggerkinds.show', $t->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    @endcan
    @can('triggerkinds_edit')
      <a role="button" href="{{ route('triggerkinds.edit', $t->id) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
    @endcan
  </td>
  </tr>
@endforeach
</table>