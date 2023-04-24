<table class="table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Category Title</th>
            <th>Category Slug</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @foreach($result as $categoryData)
            <tr>
                <td>{{ ((($result->currentPage() - 1 ) * $result->perPage() ) + $loop->iteration) . '.' }}</td>
                <td>{{ $categoryData->title }}</td>
                <td>{{ $categoryData->slug }}</td>
                <td>
                    @switch($categoryData->status)
                        @case(1)
                            Active
                            @break
                        @case(2)
                            Inactive
                            @break
                        @default
                            ""
                    @endswitch
                </td>
                <td>
                    <div class="action-icon">
                        <button type="button" class="btn btn-success edit-btn editcategory" data-bs-toggle="modal" data-bs-target="#categoryModal" data-id="{{ encrypt($categoryData->id) }}">Edit</button>
                        <a type="button" class="btn btn-danger" data-id="{{ encrypt($categoryData->id) }}" id="deleteCategory">Delete</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div>
    {!! $result->links() !!}
</div>