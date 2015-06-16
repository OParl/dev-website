@extends ('base')

@section ('content')
    <div class="row">
        <div class="col-xs-12 col-md-3">
           <div class="panel panel-default panel-toc">
               <div class="panel-heading">
                   <h3 class="panel-title">Inhaltsverzeichnis</h3>
               </div>
               <div class="panel-body">
                   <ul>
                      @foreach ($livecopy->getHeadlines() as $headline)
                          <li data-level="{{ $headline['level'] }}">{{ $headline['text'] }}</li>
                      @endforeach
                   </ul>
               </div>
           </div>
        </div>
        <div class="col-xs-12 col-md-9">
            {!! $livecopy !!}

            <div id="endnotes">
                <h3>Anmerkungen</h3>
            </div>
        </div>
    </div>
@stop