<table class="table">
<tr>
  <td>{{ __('id') }}</td>
  <td>{{ __('value') }}</td>
  <td>{{ __('trend') }}</td>
  <td></td>
</tr>
@foreach($grades as $g)
  <tr>
  <td>{{ $g->id }}</td>
  <td>{{ $g->value }}</td>
  <td>{{ $g->trend }}</td>
  <td>
    @can('grades_show')
      <a role="button" href="{{ route('grades.show', $g->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    @endcan
    @can('grades_edit')
      <a role="button" href="{{ route('grades.edit', $g->id) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
    @endcan
  </td>
  </tr>
@endforeach
</table>