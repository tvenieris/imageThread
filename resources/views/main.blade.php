<!DOCTYPE html>
<html>
    <head>
        <title>ImageThread</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div>
     
            <div>
                <div>
                    <div>
                        <div>Posts:</div>
                        <div>{{ $posts_amount or '0' }}</div>
                    </div>
                    <div>
                        {!! Form::open() !!}
                    <div class="form-group">
                            {!! Form::submit('Export', null, ['class' => 'btn btn-primary form-control']) !!}
                    </div>
                        {!! Form::close() !!}
                    </div>
                    <div>
                        <div>Views:</div>
                        <div>{{ $views_amount or '0' }}</div>
                    </div>
                </div>
                
                <div>
                    {!! Form::open(['url' => '/api/posts/create', 'files' => true]) !!}
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
                    {!! Form::close() !!}
                </div>
                
@forelse ($posts as $post)
                <div>
                    <h3>{{ $post->title }}</h3>
                    <div>
                        <img src="{{ url('/uploads') . '/' . $post->image_path }}" alt="Posted image"/>
                    </div>
                </div>
@empty
    <p>No posts</p>
@endforelse

            </div>

        </div>
    </body>
</html>