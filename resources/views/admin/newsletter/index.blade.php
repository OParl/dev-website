@extends ('admin.base')

@section ('content')
    <div class="col-md-10 col-md-offset-1">
        @include ('admin.errors')
    </div>

    <div class="col-md-10 col-md-offset-1">
        {{--
            TODO:

            - Manage lists
            - Manage subscribers
            - Compose messages
            - Schedule messages

        --}}

        {{--
        <div class="row">
            <div class="col-xs-1">
                <span class="glyphicon glyphicon-triangle-left"></span>
            </div>
            <div class="col-xs-1 col-xs-offset-10">
                <span class="glyphicon glyphicon-triangle-right"></span>
            </div>
        </div>
        --}}

        <ul role="tablist" class="nav nav-tabs">
            <li class="active" role="presentation"><a href="#lists" aria-controls="lists" role="tab" data-toggle="tab">Listen</a></li>
            <li role="presentation"><a href="#subscribers" aria-controls="subscriptions" role="tab" data-toggle="tab">Abonnenten</a></li>
            <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Nachrichten</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" role="tabpanel" id="lists">
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Beschreibung</th>
                            <th>Funktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $list)
                            <tr>
                                <td>{{ $list->id }}</td>
                                <td>{{ $list->name }}</td>
                                <td>{{ $list->description }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">{{ $lists->render() }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="tab-pane" role="tabpanel" id="subscribers">
                <table class="table table-condensed table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>E-Mail</th>
                        <th>Abonnent seit</th>
                        <th>Funktionen</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">TODO: paging</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="tab-pane" role="tabpanel" id="messages">
                <table class="table table-condensed table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Liste</th>
                        <th>Betreff</th>
                        <th>Erstellt am</th>
                        <th>Zuletzt bearbeitet</th>
                        <th>Gesendet</th>
                        <th>Funktionen</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                           <td colspan="7">TODO: paging</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@stop
