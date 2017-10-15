@extends("admin::layouts.master")

@section("content")

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <h2>
                <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                {{ trans("roles::roles.roles") }}
            </h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route("admin") }}">{{ trans("admin::common.admin") }}</a>
                </li>
                <li>
                    <a href="{{ route("admin.roles.show") }}">{{ trans("roles::roles.roles") }}
                        ({{ $roles->total() }})</a>
                </li>
            </ol>
        </div>
        <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">

            <a href="{{ route("admin.roles.create") }}" class="btn btn-primary btn-labeled btn-main"> <span
                    class="btn-label icon fa fa-plus"></span> {{ trans("roles::roles.add_new") }}</a>

        </div>
    </div>

    <form action="" method="post" class="action_form">

        <div class="wrapper wrapper-content fadeInRight">

            @include("admin::partials.messages")

            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="per_page" value="{{ Request::get('per_page') }}"/>

            <div class="row">

                <div class="col-lg-4"></div>

                <div class="col-lg-4"></div>

                <div class="col-lg-4">
                    <form action="" method="get" class="search_form">
                        <div class="input-group">
                            <input name="q" value="{{ Request::get("q") }}" type="text" class=" form-control"
                                   placeholder="{{ trans("roles::roles.search_roles") }} ...">
                            <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                        </span>
                        </div>
                    </form>
                </div>

            </div>

            <br/>

            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <h5> {{ trans("roles::roles.roles") }} </h5>
                </div>

                <div class="ibox-content">

                    @if(count($roles))

                        <div class="row">

                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 action-box">
                                <select name="action" class="form-control pull-left">
                                    <option value="-1"
                                            selected="selected">{{ trans("roles::roles.bulk_actions") }}
                                    </option>
                                    <option value="delete">{{ trans("roles::roles.delete") }}</option>
                                </select>
                                <button type="submit"
                                        class="btn btn-primary pull-right">{{ trans("roles::roles.apply") }}
                                </button>
                            </div>

                            <div class="col-lg-6 col-md-4 hidden-sm hidden-xs"></div>

                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">

                                <select class="form-control chosen-select chosen-rtl per_page_filter">

                                    <option value="" selected="selected">
                                        -- {{ trans("roles::roles.per_page") }} --
                                    </option>

                                    @foreach (array(10, 20, 30, 40) as $num)

                                        <option value="{{ $num }}"
                                                @if ($num == $per_page) selected="selected" @endif>{{ $num }}
                                        </option>

                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-hover">

                                <thead>

                                <tr>
                                    <th style="width:35px"><input type="checkbox" class="check_all i-checks"
                                                                  name="ids[]"/>
                                    </th>
                                    <th>{{ trans("roles::roles.name") }}</th>
                                    <th>{{ trans("roles::roles.actions") }}</th>
                                </tr>

                                </thead>

                                <tbody>

                                @foreach ($roles as $role)

                                    <tr>
                                        <td>
                                            <input type="checkbox" class="i-checks" name="id[]"
                                                   value="{{ $role->id }}"/>
                                        </td>

                                        <td>
                                            <a class="text-navy"
                                               href="{{ route("admin.roles.edit", ["id" => $role->id]) }}">
                                                <strong> {{ $role->name }}</strong>
                                            </a>
                                        </td>

                                        <td class="center">

                                            <a href="{{ route("admin.roles.edit", ["id" => $role->id]) }}">
                                                <i class="fa fa-pencil text-navy"></i>
                                            </a>

                                            <a class="ask" message="{{ trans("roles::roles.sure_delete") }}"
                                               href="{{ route("admin.roles.delete", ["id" => $role->id]) }}">
                                                <i class="fa fa-times text-navy"></i>
                                            </a>

                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>

                        </div>

                        <div class="row">
                            <div class="col-lg-12 text-center">
                                {{ trans("roles::roles.page") }}
                                {{ $roles->currentPage() }}
                                {{ trans("roles::roles.of") }}
                                {{ $roles->lastPage() }}
                            </div>
                            <div class="col-lg-12 text-center">
                                {{ $roles->appends(Request::all())->render() }}
                            </div>
                        </div>

                    @else

                        {{ trans("roles::roles.no_records") }}

                    @endif

                </div>
            </div>

        </div>

    </form>

@stop

@section("footer")

    <script>

        $(document).ready(function () {

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            $('.check_all').on('ifChecked', function (event) {
                $("input[type=checkbox]").each(function () {
                    $(this).iCheck('check');
                    $(this).change();
                });
            });
            $('.check_all').on('ifUnchecked', function (event) {
                $("input[type=checkbox]").each(function () {
                    $(this).iCheck('uncheck');
                    $(this).change();
                });
            });

            $(".per_page_filter").change(function () {
                var base = $(this);
                var per_page = base.val();
                location.href = "{{ route("admin.roles.show") }}?per_page=" + per_page;
            });

        });

    </script>
@stop

