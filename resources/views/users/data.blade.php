<table class="table">
    <thead>
        <tr>
            <th>No.</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @foreach($result as $usersData)
            <tr>
                <td>{{ ((($result->currentPage() - 1 ) * $result->perPage() ) + $loop->iteration) . '.' }}</td>
                <td>{{ $usersData->firstname }}</td>
                <td>{{ $usersData->lastname }}</td>
                <td>{{ $usersData->email }}</td>
                <td>{{ $usersData->phone }}</td>
                <td>
                    <div class="action-icon">
                        <button type="button" class="btn btn-success edit-btn editusers" data-bs-toggle="modal" data-bs-target="#usersModal" data-id="{{ encrypt($usersData->id) }}">Edit</button>
                        <a type="button" class="btn btn-danger" data-id="{{ encrypt($usersData->id) }}" id="deleteUsers">Delete</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div>
    {!! $result->links() !!}
</div>