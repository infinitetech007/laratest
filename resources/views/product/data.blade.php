<table class="table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Product Category</th>
            <th>Product Title</th>
            <th>Product Slug</th>
            <th>Featured Image</th>
            <th>Gallery</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @foreach($result as $productData)
            <tr>
                <td>{{ ((($result->currentPage() - 1 ) * $result->perPage() ) + $loop->iteration) . '.' }}</td>
                <td>
                    @php 
                        $category = explode(",", $productData->category);
                        $data = App\Models\Product::getCategory($category);
                    @endphp
                    {{ implode(', ',$data) }}
                </td>
                <td>{{ $productData->title }}</td>
                <td>{{ $productData->slug }}</td>
                <td class="product-image">
                    <img src="{{ asset($productData->featured_image) }}" alt="Media Thumbnail">
                </td>
                <td class="product-image">
                    @php 
                        $m_img = explode(",", $productData->gallery);
                    @endphp
                    @foreach($m_img as $key => $val)
                        <img src="{{ asset($val) }}">
                    @endforeach
                </td>
                <td>{!! $productData->description !!}</td>
                <td>
                    @switch($productData->status)
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
                        <button type="button" class="btn btn-success edit-btn editproduct" data-bs-toggle="modal" data-bs-target="#productModal" data-id="{{ encrypt($productData->id) }}">Edit</button>
                        <a type="button" class="btn btn-danger" data-id="{{ encrypt($productData->id) }}" id="deleteProduct">Delete</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div>
    {!! $result->links() !!}
</div>