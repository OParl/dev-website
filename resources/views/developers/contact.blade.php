@extends ('base')

@section ('subheader')

@stop

@section ('content')
    <div class="section">
        <p>@lang('app.contact.info')</p>

        <ul class="list-unstyled">
            <li>
                <i class="fab fa-github" aria-hidden="true"></i>
                <a href="https://github.com/OParl">GitHub</a>
                <p>
                    @lang('app.contact.github')
                </p>
            </li>

            <li>
                <i class="fa fa-sticky-note" aria-hidden="true"></i>
                <a href="https://lists.okfn.org/mailman/listinfo/oparl-tech">@OParl-Tech</a>
                <p>
                    @lang('app.contact.mailinglist')
                </p>
            </li>

            {{--<li>--}}
                {{--<i class="fa fa-info" aria-hidden="true"></i>--}}
                {{--<a href="{{ route('contact.index') }}">@lang('app.contact.form_info')</a>--}}
                {{--<p>--}}
                    {{--@lang('app.contact.form')--}}
                {{--</p>--}}
            {{--</li>--}}
        </ul>
    </div>
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/developers.js') }}"></script>
@stop
