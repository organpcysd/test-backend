<div class="bg-white px-4 py-4 rounded shadow m-1">
    <div class="row">
        <div class="col-sm-6">
            {{ json_decode($category->title)->th }}
        </div>
        <div class="col-sm-6">
            <div class="float-right">
                <a class="btn btn-warning btn-sm" href="{{ route('productcategory.edit',['productcategory' => $category->slug]) }}"><i class="fas fa-pen"></i></a>
                <button class="btn btn-danger btn-sm" onclick="productcategory_confirmdelete('{{ url('admin/productcategory') . '/' . $category->id }}')"><i class="fas fa-trash-alt"></i></button>
            </div>
        </div>
    </div>
</div>
<x-categories :categories="$category->children"/>
