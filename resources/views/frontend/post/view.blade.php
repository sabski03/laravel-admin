@extends('layouts.app')

@section('title', "$post->meta_title")
@section('meta_description', "$post->meta_description")
@section('meta_keyword', "$post->meta_keyword")

@section('content')
    <div class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="category-heading">
                        <h4>{!! $post->name !!}</h4>
                    </div>


                    <div class="card card-shadow mt-4">
                        <div class="card-body">
                            {!! $post->description !!}
                        </div>
                    </div>


                    <div class="comment-are mt-4">

                        @if(session('message'))
                            <h6 class="alert alert-warning mb-3">{{ session('message') }}</h6>
                        @endif

                        <div class="card card-body">
                            <h6 class="card-title">Leave A Comment</h6>
                            <form action="{{ url('comments') }}" method="POST">
                                @csrf
                                <input type="hidden" name="post_slug" value="{{ $post->slug }}">
                                <textarea name="comment_body" class="form-control" rows="3" required></textarea>
                                <button type="submit" class="btn btn-primary mt-3">Submit</button>
                            </form>
                        </div>
                        @forelse($post->comments as $comment)

                        <div class="comment-container card card-body shadow-sm mt-3">
                            <div class="detail-area">
                                <h6 class="user-name mb-1">
                                    @if ($comment->user)
                                        {{ $comment->user->name }}
                                    @endif
                                    <small class="ms-3 text-primary">Commented On : {{ $comment->created_at->format('d-m-Y') }}</small>
                                </h6>
                                <p class="user-coment mb-1">
                                    {!! $comment->comment_body !!}
                                </p>
                            </div>
                            @if( Auth::check() && Auth::id() == $comment->user->id || Auth::id() == 1)
                            <div>
                                <button type="button" value="{{ $comment->id }}" class="deleteComment btn btn-danger btn-sm me-2 float-end">Delete</button>
                                <a href="" class="btn btn-primary btn-sm me-2 float-end">Edit</a>
                            </div>
                            @endif
                        </div>

                            @empty
                            <div class="card card-body shadow-sm mt-3">
                                <h6>No Comments Found</h6>
                            </div>
                            @endforelse
                    </div>


                </div>


                <div class="col-md-4">
                    <div class="border p-2 my-2">
                        <h4>area</h4>
                    </div>
                    <div class="border p-2 my-2">
                        <h4>area</h4>
                    </div>
                    <div class="border p-2 my-2">
                        <h4>area</h4>
                    </div>
                    <div class="border p-2 my-2">
                        <h4>area</h4>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h4>Latest Posts</h4>
                        </div>
                        <div class="card-body">
                            @foreach ($latest_posts as $latest_post_item)

                                <a href=" {{ url('tutorial/' . $latest_post_item->category->slug . '/' . $latest_post_item->slug) }}" class="text-decoration-none">
                                    <h6> > {{ $latest_post_item->name }}</h6>
                                </a>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function (){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.deleteComment', function(){

                if(confirm('Are you sure you want to delete this comment?')){
                    var thisClicked = $(this);
                    var comment_id = thisClicked.val();

                    $.ajax({
                        type: "POST",
                        url: "/delete-comment",
                        data: {
                            'comment_id': comment_id,
                        },
                        success: function (res){
                            if(res.status == 200){
                                thisClicked.closest('.comment-container').remove();
                                alert(res.message);
                            }else{
                                alert(res.message);
                            }
                        }
                    })
                }

            });
        });
    </script>

@endsection
