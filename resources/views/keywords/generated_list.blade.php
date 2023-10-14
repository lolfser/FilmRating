<table class="table">
<tr>
  <td>{{ __('id') }}</td>
  <td>{{ __('name') }}</td>
  <td></td>
</tr>
@foreach($keywords as $k)
  <tr>
  <td>{{ $k->id }}</td>
  <td>{{ $k->name }}</td>
  <td>
    @can('keywords_show')
      <a role="button" href="{{ route('keywords.show', $k->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
    @endcan
    @can('keywords_edit')
      <a role="button" href="{{ route('keywords.edit', $k->id) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
    @endcan
  </td>
  </tr>
@endforeach
</table>