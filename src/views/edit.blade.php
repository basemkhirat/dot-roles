@extends("admin::layouts.master")

@section("content")

    <form action="" method="post">

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <h2>
                    <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                    {{ trans("roles::roles.edit") }}
                </h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route("admin") }}">{{ trans("admin::common.admin") }}</a>
                    </li>
                    <li>
                        <a href="{{ route("admin.roles.show") }}">{{ trans("roles::roles.roles") }}</a>
                    </li>
                    <li class="active">
                        <strong>{{ trans("roles::roles.edit") }}</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">

                @if($role)
                    <a href="{{ route("admin.roles.create") }}" class="btn btn-primary btn-labeled btn-main">
                        <span class="btn-label icon fa fa-plus"></span> {{ trans("roles::roles.add_new") }}
                    </a>
                @endif

                <button type="submit" class="btn btn-flat btn-danger btn-main">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    {{ trans("roles::roles.save") }}
                </button>

            </div>
        </div>

        <div class="wrapper wrapper-content fadeInRight">

            @include("admin::partials.messages")

            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            <div class="row">
                <div class="col-md-12">

                    <div class="panel">

                        <div class="panel-body">

                            <div class="form-group">
                                <input name="name" value="{{ @Request::old("name", $role->name) }}"
                                       class="form-control input-lg" value=""
                                       placeholder="{{ trans("roles::roles.name") }}"/>
                            </div>

                            @foreach($plugins as $plugin)

                                @if(count($plugin->getPermissions()))

                                    <div class="panel panel-default ">

                                        <div class="panel-heading">
                                            <a class="accordion-toggle text-navy" data-toggle="collapse"
                                               href="#collapse-{{ $plugin->getKey() }}">
                                                <strong>{{ ucfirst($plugin->getName()) }}</strong>
                                            </a>
                                        </div>

                                        <div id="collapse-{{ $plugin->key }}" class="panel-collapse in">
                                            <div class="panel-body">

                                                @foreach($plugin->getPermissions() as $slug)

                                                    <label class="checkbox">
                                                        <input
                                                            @if($role and in_array($plugin->getKey() . "." . $slug, $role_permissions))
                                                            checked="checked"
                                                            @endif
                                                            type="checkbox" name="permissions[]"
                                                            value="{{ $plugin->getKey() . "." . $slug }}"
                                                            class="switcher permission-switcher switcher-sm">

                                                        &nbsp;

                                                        <span>
                                                                    {{ ucfirst(trans($plugin->getKey() . "::" . $plugin->getKey() . ".permissions." . $slug)) }}
                                                                </span>
                                                    </label>

                                                @endforeach

                                            </div>
                                        </div>
                                    </div>

                                @endif

                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

@stop

@section("footer")

    <script>
        $(document).ready(function () {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.permission-switcher'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html);
            });
        });
    </script>

@stop
