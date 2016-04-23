<!DOCTYPE html>
<html>
    <head>
        <title>ImageThread</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="/assets/js/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" src="/assets/js/imageThread.js"></script>
    </head>
    <body>
        <div>
     
            <div>
                <div>
                    <div>
                        <div>Posts:</div>
                        <div id="posts_amount_cnt">{{ $posts_amount or '0' }}</div>
                    </div>
                    <div>
                        {!! Form::open(['url' => '/api/posts/export_csv', 'method' => 'get']) !!}
                            {!! Form::submit('Export CSV') !!}
                        {!! Form::close() !!}
                        {!! Form::open(['url' => '/api/posts/export_xls', 'method' => 'get']) !!}
                            {!! Form::submit('Export Excel') !!}
                        {!! Form::close() !!}
                    </div>
                    <div>
                        <div>Views:</div>
                        <div id="views_amount_cnt">{{ $views_amount or '0' }}</div>
                    </div>
                </div>
                
                <div>
                    {!! Form::open(['url' => '/api/posts/create', 'files' => true, 'name' => 'new_post', 'id' => 'new_post']) !!}
                    <div class="form-group">
                            {!! Form::label('title', 'Title:') !!}
                            {!! Form::text('title', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                            {!! Form::label('image', 'Image:') !!}
                            {!! Form::file('image', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                            {!! Form::submit('Add', null, ['class' => 'btn btn-primary form-control']) !!}
                    </div>
@if (!empty($last_error))
                    <div class="form-group" class="error">
                            {{ $last_error['description'] }}
                    </div>
@endif
                    {!! Form::close() !!}
                </div>
                
@forelse ($posts as $post)
                <div>
                    <h3>{{ $post->title or 'Untitled'}}</h3>
                    <div>
                        <img src="{{ $post->getURL() }}" alt="Posted image"/>
                    </div>
                </div>
@empty
    <p>No posts</p>
@endforelse

            </div>

        </div>
    </body>
</html>