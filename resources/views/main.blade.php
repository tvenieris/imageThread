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
                            <input type="bitton" name="export" value="Export button"/>
                        {!! Form::close() !!}
                    </div>
                    <div>
                        <div>Views:</div>
                        <div>#views</div>
                    </div>
                </div>
                
                <div>
                    <form>
                        <input type="text" name="image_title" value=""/>
                        <input type="file" name="image_file"/>
                    </form>
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