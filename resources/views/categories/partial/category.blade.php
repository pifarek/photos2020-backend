@foreach($categories as $category)
    <tr>
        <td>
            {!! str_repeat('&mdash;', $level) !!} {{ $category->name }}
            <span class="badge badge-pill badge-primary">{{ $category->media->count() }}</span>
        </td>
        <td>
            <span class="badge badge-success">{{ $category->created_at->format('d F Y') }}</span>
        </td>
        <td>
            @if($category->status === 'published')
            <span class="badge badge-success">Published</span>
            @elseif($category->status === 'pending')
            <span class="badge badge-primary">Pending</span>
            @endif
        </td>
        <td style="width: 1%; white-space: nowrap;">
            <a href="{{ route('media.index', ['category' => $category->id]) }}" class="btn btn-sm btn-primary"><i class="far fa-eye"></i></a>
            <a href="{{ route('categories.edit', [$category->id]) }}" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></a>
            <a href="#" data-id="{{ $category->id }}" data-action="category-remove" data-id="{{ $category->id }}" class="btn btn-sm btn-warning"><i class="fas fa-trash-alt"></i></a>
        </td>
    </tr>

    @if($category->children()->count())
        @include('categories.partial.category', ['categories' => $category->children()->get(), 'level' => ++$level])
        @php $level-- @endphp
    @endif
@endforeach
