<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>OParl Spezifikation</title>

        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />
        <link href="{{ asset('/css/lib.css') }}" rel="stylesheet" />

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include ('header')

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('/js/lib.js') }}"></script>
        <script src="{{ asset('/js/app.js') }}"></script>
    </body>
</html>
