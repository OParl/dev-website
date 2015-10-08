@extends ('admin.base')

@section ('content')
    <div class="col-md-10 col-md-offset-1">
        <h2>Versionsinformationen für {{ $build->short_hash }}</h2>

        <form class="form-horizontal" action="{{ route('admin.specification.save', $build->id) }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" value="{{ $build->id }}" />

            <div class="form-group">
                <div class="col-sm-3"><label for="created_at">Erstellt am:</label></div>
                <div class="col-sm-9">
                    <p class="form-control-static" id="created_at">
                        {{ $build->created_at->format('d.m.Y \u\m H:i:s \U\h\r') }}
                    </p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-3"><label for="hash">Hash:</label></div>
                <div class="col-sm-9">
                    <p class="form-control-static" id="hash">{{ $build->hash }}</p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-3"><label for="human_version">Angezeigter Versionstext:</label></div>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="human_version" id="human_version"
                           value="{{ old('human_version', $build->human_version) }}"/>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-3"><label for="commit_message">Orginalversionstext:</label></div>
                <div class="col-sm-9">
                    <p class="form-control-static" id="commit_message">{{ $build->commit_message }}</p>
                </div>
            </div>

            <hr />

            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" {{ old('persistent', $build->persistent) ? 'checked' : '' }} name="persistent" />
                            Persistent
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" {{ old('displayed', $build->displayed) ? 'checked' : '' }} name="displayed" />
                            Angezeigt
                        </label>
                    </div>
                </div>
            </div>

            <hr />

            <div class="form-group">
                <div class="col-md-3 col-md-offset-9 text-right">

                    <input type="submit" class="btn btn-primary" value="Speichern!">
                </div>
            </div>
        </form>
    </div>
@stop
