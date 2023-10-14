<table class="table">
<tr>
  <td>{{ __('users_id') }}</td>
  <td>{{ __('initials') }}</td>
  <td>{{ __('comment') }}</td>
  <td></td>
</tr>
@foreach($viewers as $v)
  <tr>
  <td>{{ $v->users_id }}</td>
  <td>{{ $v->initials }}</td>
  <td>{{ $v->comment }}</td>
  <td>
    @can('viewers_show')
      <a role="button" href="{{ route('viewers.show', $v->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    @endcan
    @can('viewers_edit')
      <a role="button" href="{{ route('viewers.edit', $v->id) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
    @endcan
  </td>
  </tr>
@endforeach
</table>