<!DOCTYPE html>
<html lang="en-us" class="no-js">
<meta charset="utf-8" />
<head>
<title>ImageThread</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="/assets/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="/assets/js/imageThread.js"></script>
<style>
@import url("http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css");
@import url("http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700");
*{margin:0; padding:0}
body{background:#294072; font-family: 'Source Sans Pro', sans-serif}
.form{width:400px; margin:0 auto; background:#1C2B4A; margin-top:30px}
.post{display: inline-block;margin:0 auto; background:#1C2B4A; margin-top:20px;padding:12px;text-align:center;}
.header{height:44px; background:#17233B}
.header h2{height:44px; line-height:44px; color:#fff; text-align:center}
.header h3{height:44px; line-height:44px; color:#fff; text-align:center; font-size:18px;}
.login{padding:0 20px}
.login span.un{width:3%; text-align:center; color:#0C6; border-radius:3px 0 0 3px}
.text{background:#12192C; width:97%; border-radius:0 3px 3px 0; border:none; outline:none; color:#999; font-family: 'Source Sans Pro', sans-serif} 
.text,.login span.un{display:inline-block; vertical-align:top; height:40px; line-height:40px; background:#12192C;}

.btn{height:40px; border:none; background:#2c7bdc; width:100%; outline:none; font-family: 'Source Sans Pro', sans-serif; font-size:20px; font-weight:bold; color:#eee; border-bottom:solid 3px #115bb6; border-radius:3px; cursor:pointer}
ul li{height:40px; margin:15px 0; list-style:none}
.span{display:table; width:100%; font-size:14px;}
.ch{display:inline-block; width:50%; color:#CCC}
.ch a{color:#CCC; text-decoration:none}
.ch:nth-child(2){text-align:right}
.ch2{display:inline-block; width:100%; color:#CCC}
.ch2 a{color:#CCC; text-decoration:none}
.ch2:nth-child(2){text-align:right}
/*social*/
.social{height:30px; line-height:30px; display:table; width:100%}
.social div{display:inline-block; width:42%; color:#eee; font-size:12px; text-align:center; border-radius:3px}
.social div i.fa{font-size:16px; line-height:30px}
.fb{background:#3B5A9A; border-bottom:solid 3px #036} .tw{background:#2CA8D2; margin-left:16%; border-bottom:solid 3px #3399CC}
/*bottom*/
.sign{width:90%; padding:0 5%; height:50px; display:table; background:#17233B}
.sign div{display:inline-block; width:50%; line-height:50px; color:#ccc; font-size:14px}
.up{text-align:right}
.up a{display:block; background:#096; text-align:center; height:35px; line-height:35px; width:48%; font-size:16px; text-decoration:none; color:#eee; border-bottom:solid 3px #006633; border-radius:3px; font-weight:bold; margin-right:5px;float: left;}
.up2 a{display:block; background:#096; text-align:center; height:35px; line-height:35px; width:48%; font-size:16px; text-decoration:none; color:#eee; border-bottom:solid 3px #006633; border-radius:3px; font-weight:bold; margin-right:5px;float: right;}
.cntr{text-align: center;}
@media(max-width:480px){ .form{width:100%}}
</style>
<link rel="stylesheet" type="text/css" href="/assets/CustomFileInputs/css/component.css" />
<script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>
</head>
<body>
    <div class="form">
        <div class="header"><h2>imageThread</h2></div>
        <div class="login">
            <div class="span">
                <span class="ch">
                    <span>Posts:</span>
                    <span id="posts_amount_cnt">{{ $posts_amount or '0' }}</span>
                </span>
                <span class="ch">
                    <span>Views:</span>
                    <span id="views_amount_cnt">{{ $views_amount or '0' }}</span>
                </span>
                <div class="up"><a href="/api/posts/export_csv">Export CSV</a></div>
                <div class="up2"><a href="/api/posts/export_xls">Export Excel</a></div>
            </div>
            {!! Form::open(['url' => '/api/posts/create', 'files' => true, 'name' => 'new_post', 'id' => 'new_post']) !!}
                <ul>
                    <li>
                        <span class="un"><i class="fa"></i></span>{!! Form::text('title', null, ['class' => 'text', 'placeholder' => 'Title']) !!}
                    </li>
                    <li>
                        <div style="text-align: center;">
                        <input type="file" name="image" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple=""/>
			<label for="file-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg> <span>Choose a file…</span></label>
                        </div>                        
                    </li>
                    <li>
                        <div style="margin-top: 20px">
                        <input type="submit" value="Add" class="btn">
                        </div>
                    </li>
                </ul>
            {!! Form::close() !!}
@if (!empty($last_error))
                <span class="ch2">
                    <span>{{ $last_error['description'] }}</span>
                </span>
@endif

        </div><br/>
    </div>
    
    <div class="cntr">
@forelse ($posts as $post)
    <div class="post">
        <div class="header"><h3>{{ $post->title or 'Untitled'}}</h3></div>
        <div>
            <img src="{{ $post->getURL() }}" alt="Posted image"/>
        </div>
    </div>
    <br/>
@empty
    <div class="post">
        <div class="header"><h3>No posts</h3></div>
    </div>
@endforelse
    </div>
<script src="/assets/CustomFileInputs/js/custom-file-input.js"></script>
</body>
</html>