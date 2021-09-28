@extends('backend.layouts.headerfooter')
@section ('title', 'Categories')
@section('content')

    @push('post-css')
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/multiselect/css/multi-select.css') }}">
    @endpush
    <!-- Category Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Category Header (Page header) -->
        <section class="content-header">
            <h1>
                Categories
                <small>
                    List | Add | Update | Delete Categories
                </small>
            </h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.categories.index') }}"><i class="fa fa-anchor"></i> Categories</a></li>
                    <li class="active">{{ request()->routeIs('admin.categories.edit') ? 'Update Category' : 'Add New Category' }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            
            <div class="row">
                <div class="col-md-12" id="addCategory">
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add || Edit Category's Details</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ request()->routeIs('admin.categories.edit') ? route('admin.categories.update',$category) : route('admin.categories.store') }}" enctype="multipart/form-data">
                    
                            @csrf

                            @if(request()->routeIs('admin.categories.edit'))
                                @method('PUT')
                            @endif
                            
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-text-width"></i>  Title
                                            </span>
                                            <input type="text" class="form-control" value="{{ request()->routeIs('admin.categories.edit') ? $category->title : (old('title') ? old('title') : '') }}" name="title" placeholder="Enter Title Here.." required />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?php $display =  request()->routeIs('admin.categories.edit') ? $category->display : (old('category') ? old('category') : 1)  ?>
                                                <input name="display" <?php echo($display == 1 ? 'checked' : ''); ?> value="1" type="checkbox">
                                            </span>
                                            <input type="text" value="Display" class="form-control" readonly="readonly">
                                            <span class="input-group-addon">
                                                <?php $cat =  request()->routeIs('admin.categories.edit') ? $category->popular : 0  ?>
                                                <input name="popular" <?php echo(@$cat == 1 ? 'checked' : ''); ?> value="1" type="checkbox">
                                            </span>
                                            <input type="text" value="Popular" class="form-control" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-image"></i> Image 
                                            </span>
                                            <input id="imageField" class="btn btn-info btn-flat" type="file" name="image" {{ request()->routeIs('admin.categories.create') ? 'required' : '' }}>
                                        </div>
                                        <small>Recommended size: 900px X 900px for best fit.</small>
                                    </div>
                                    <div class="col-md-2">
                                        @if(request()->routeIs('admin.categories.edit'))
                                            <img class="img-thumbnail pull-right" width="40%" src="{{ asset('storage/categories/thumbs/small_'.$category->image) }}">
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-anchor"></i> Parent Category
                                            </span>
                                            <select name="parent_id" class="form-control select2">
                                                <option value="0">None</option>
                                                @php
                                                    $parentId = old('parent_id') ? old('parent_id') : (request()->routeIs('admin.categories.edit') ? $category->parent_id : '');
                                                @endphp
                                                @foreach($parent_categories as $pCat)
                                                    <option {{ $parentId == $pCat->id ? 'selected' : '' }} value="{{ $pCat->id }}">{{ $pCat->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <br>
                                @if(request()->routeIs('admin.categories.edit') && $category->child == 0)
                                
                                    @php
                                        $category_products = $category->category_products()->pluck('product_id')->all();
                                        // dd($category_products);
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-anchor"></i> Select Products
                                                </span>
                                                <select id="productIds" name="product_id[]" class="form-control" multiple data-allow-clear="true">
                                                    @foreach($products as $product)
                                                        <option {{ in_array($product->id, $category_products) ? 'selected' : '' }}  value="{{ $product->id }}">{{ $product->title }} ({{ $product->sku }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(request()->routeIs('admin.categories.create'))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-anchor"></i> Selecte Products
                                                </span>
                                                <select id="productIds" name="product_id[]" class="form-control" multiple data-allow-clear="true">
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- <div class="row">
                                    <div class="col-md-12">

                                        <div class="input-group">
                                            <label class="input-group-addon" for="content"> &nbsp;&nbsp;&nbsp;Content</label>
                                            <textarea class="form-control ckeditor" name="content" rows="10" cols="80" placeholder="Long Content">
                                                {{ request()->routeIs('admin.categories.edit') ? $category->content : '' }}
                                            </textarea>
                                        </div>
                                    </div>
                                </div> --}}

                            </div>
                            <div class="box-footer">

                                <?php if (request()->routeIs('admin.categories.edit')) { ?>
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">CANCEL</a> &nbsp;
                                    <button type="submit" name="submitEdit" class="btn btn-primary pull-right">UPDATE CATEGORY
                                    </button>
                                <?php } else { ?>
                                    <a onclick="cancleAdd()" class="btn btn-danger">CANCEL</a> &nbsp;
                                    <button type="submit" name="submit" class="btn btn-success pull-right">SAVE CATEGORY
                                    </button>
                                <?php } ?>
                            </div>
                            
                        </form>
                        <!-- form ends -->
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>
        </section>
        <!-- /.content -->
    </div>
    

@endsection

@push('scripts')
    <script src="{{ asset('backend/plugins/quicksearch-master/jquery.quicksearch.js') }}"></script>
    <script src="{{ asset('backend/plugins/multiselect/js/jquery.multi-select.js') }}"></script>
    <script>
        $(function () {
            // $("#productIds").multiSelect();
            $('#productIds').multiSelect({
                keepOrder: true,
                selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Search to Select'>",
                selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Search to Remove'>",
                afterInit: function(ms){
                    var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                        if (e.which === 40){
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                        if (e.which == 40){
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
                },
                afterSelect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });
        });

    </script>
@endpush