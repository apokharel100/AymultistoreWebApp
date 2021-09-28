@extends('backend.layouts.headerfooter')
@section ('title', 'Blogs')
@section('content')
    
    <!-- Blog Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Blog Header (Page header) -->
        <section class="content-header">
            <h1>
                Blogs
                <small>
                    List | Add | Update | Delete Blogs
                </small>
            </h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.blogs.index') }}"><i class="fa fa-rebel"></i> Blogs</a></li>
                    <li class="active">{{ request()->routeIs('admin.blogs.edit') ? 'Update Blog' : 'Add New Blog' }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            
            <div class="row">
                <div class="col-md-12" id="addBlog">
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add || Edit Blog's Details</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ request()->routeIs('admin.blogs.edit') ? route('admin.blogs.update',$blog) : route('admin.blogs.store') }}" enctype="multipart/form-data">
                    
                            @csrf

                            @if(request()->routeIs('admin.blogs.edit'))
                                @method('PUT')
                            @endif
                            
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">

                                            <span class="input-group-addon">
                                                <i class="fa fa-text-width"></i>  Title
                                            </span>
                                            <input type="text" class="form-control" value="{{ request()->routeIs('admin.blogs.edit') ? $blog->title : (old('title') ? old('title') : '') }}" name="title" placeholder="Enter Title Here.." required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">

                                            <span class="input-group-addon">
                                                <i class="fa fa-user"></i>  Author
                                            </span>
                                            <input type="text" class="form-control" value="{{ request()->routeIs('admin.blogs.edit') ? $blog->author : (old('author') ? old('author') : '') }}" name="author" placeholder="Enter Author Name Here.." />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?php $display =  request()->routeIs('admin.blogs.edit') ? $blog->display : (old('blog') ? old('blog') : 1)  ?>
                                                <input name="display" <?php echo($display == 1 ? 'checked' : ''); ?> value="1" type="checkbox">
                                            </span>
                                            <input type="text" value="Display" class="form-control" readonly="readonly">

                                            <span class="input-group-addon">
                                                <?php $featured =  request()->routeIs('admin.blogs.edit') ? $blog->featured : (old('blog') ? old('blog') : 0)  ?>
                                                <input name="featured" <?php echo($featured == 1 ? 'checked' : ''); ?> value="1" type="checkbox">
                                            </span>
                                            <input type="text" value="Featured" class="form-control" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-image"></i> Image 
                                            </span>
                                            <input id="imageField" class="btn btn-info btn-flat" type="file" name="image" {{ request()->routeIs('admin.blogs.create') ? 'required' : '' }}>
                                        </div>

                                    </div>

                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-image"></i> Gallery Images
                                            </span>
                                            <input class="btn btn-info btn-flat" type="file" name="other_images[]" multiple>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <small>
                                            <b>Featured & Gallery Image resolution:</b> 1200x800px image recommended.
                                        </small>
                                    </div>
                                </div>
                                <div class="row imageDiv" style="margin-left: 0px; margin-right: 0px;">
                                    <?php if (request()->routeIs('admin.blogs.edit')): ?>

                                        <div class="col-lg-4 col-md-3 col-xs-6" style="min-height:100px;">
                                            <img class="img-responsive" src="{{ asset('storage/blogs/'.$blog->slug.'/thumbs/thumb_'.$blog->image) }}" width="60%" alt="N/a"/>
                                        </div>

                                        <div class="col-lg-8 col-md-9 col-xs-6 ">
                                            <div class="row ">
                                                <?php
                                                    $images = Storage::files('public/blogs/'.$blog->slug.'/');
                                                ?>
                                                @for ($i = 0; $i < count($images); $i++)
                                                    @if(strpos($images[$i], $blog->image) != true)
                                                    <div class="col-md-3" style="padding-bottom: 5px; margin-right: 5px; max-width: 100px;" id="gallery_image_{{$i}}">
                                                                        
                                                        <a href="#delete_image" data-toggle="modal"
                                                           data-photo=""
                                                           onclick="delete_image('{{ $blog->slug }}', '{{ basename($images[$i]) }}', 'gallery_image_{{$i}}')"
                                                           id="" title="Delete Image">
                                                            <i style="position: absolute; top: -9px; padding: 4px; color: red;border-radius: 50%; opacity: 1;" class="close fa fa-times"></i>
                                                        </a>
                                                        <a data-fancybox="{{ $blog->title }}" href="{{ asset('storage/blogs/'.$blog->slug.'/'.basename($images[$i])) }}" data-sub-html="{{ $blog->title }}">

                                                            <img src="{{ asset('storage/').str_replace('public/blogs/'.$blog->slug.'/','/blogs/'.$blog->slug.'/thumbs/thumb_',$images[$i])}}" alt="no-image" style="max-width: 100px; padding-bottom: 4px;">
                                                        </a>
                                                    </div>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                                <br>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="input-group-addon" for="content"> &nbsp;&nbsp;&nbsp;Short Highlights</label>
                                            <textarea class="form-control" name="short_description" rows="10" cols="80" placeholder="Short Highlights">{{ request()->routeIs('admin.blogs.edit') ? $blog->short_description : '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="input-group-addon" for="content"> &nbsp;&nbsp;&nbsp;Description</label>
                                            <textarea class="form-control ckeditor" name="long_description" rows="10" cols="80" placeholder="Short Highlights">{{ request()->routeIs('admin.blogs.edit') ? $blog->long_description : '' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="box-footer">

                                <?php if (request()->routeIs('admin.blogs.edit')) { ?>
                                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-danger">CANCEL</a> &nbsp;
                                    <button type="submit" name="submitEdit" class="btn btn-primary pull-right">UPDATE BLOG
                                    </button>
                                <?php } else { ?>
                                    <a onclick="cancleAdd()" class="btn btn-danger">CANCEL</a> &nbsp;
                                    <button type="submit" name="submit" class="btn btn-success pull-right">SAVE BLOG
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
    
    <div class="modal fade modal-danger" id="delete_image">
        <div class="modal-dialog " role="document">
            <div class="modal-content bg-default">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Delete Gallery Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-white">
                    <p>Are you Sure...!!</p>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                    <button id="confirmDeleteGalleryImage" data-slug="" data-gallery-image="" data-gallery-image-id="" class="btn btn-round btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    
    <script>
        // <---------- Delete Image Modal ---------->
        function delete_image(slug, image, galleryImageId) {

            $("#confirmDeleteGalleryImage").attr('data-slug', slug);
            $("#confirmDeleteGalleryImage").attr('data-image', image);
            $("#confirmDeleteGalleryImage").attr('data-gallery-image-id', galleryImageId);
        }

        // <---------- Delete Blog Gallery Image ---------->
        jQuery("#confirmDeleteGalleryImage").click(function(){

            $("#delete_image").modal('hide');

            var slug = $(this).attr('data-slug');
            var image = $(this).attr('data-image');
            var gallery_image_id = String($(this).attr('data-gallery-image-id'));

            $.ajax({
                url : "{{ URL::route('admin.blogs.delete-gallery-image') }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                        slug: slug,
                        image: image
                    },
                cache : false,
                beforeSend : function (){
                    $('#modal-loader').show();
                },
                complete : function($response, $status){

                    if ($status != "error" && $status != "timeout") {
                        
                        var obj = jQuery.parseJSON($response.responseText);

                        if (obj.message == 'success') {
                            $("#"+gallery_image_id).remove();
                        }else{
                            toastr['error']('Something went wrong!','Error');
                        }
                        $('#modal-loader').hide();
                        // $("#pageUrl").html($response.responseText);
                        
                    }
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                    $('#modal-loader').hide();
                }
            }); 
        });
    </script>
@endpush