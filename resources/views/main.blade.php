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
                        <div>#posts</div>
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
                        <div>#views</div>
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
                
                <div>
                    <div>Image title</div>
                    <div>
                        <img src=""/>
                    </div>
                </div>
                
            </div>

        </div>
    </body>
</html>