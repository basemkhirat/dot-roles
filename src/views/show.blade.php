@extends("admin::layouts.master")

@section("content")

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <h2>
                <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                <?php echo trans("roles::roles.roles") ?>
            </h2>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo route("admin"); ?>"><?php echo trans("admin::common.admin") ?></a>
                </li>
                <li>
                    <a href="<?php echo route("admin.roles.show"); ?>"><?php echo trans("roles::roles.roles") ?>
                        (<?php echo $roles->total() ?>)</a>
                </li>
            </ol>
        </div>
        <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">
            <?php if (Gate::allows("users.create")) { ?>
            <a href="<?php echo route("admin.roles.create"); ?>" class="btn btn-primary btn-labeled btn-main"> <span
                    class="btn-label icon fa fa-plus"></span> <?php echo trans("roles::roles.add_new"); ?></a>
            <?php } ?>
        </div>
    </div>

    <div class="wrapper wrapper-content fadeInRight">

        @include("admin::partials.messages")

        <input type="hidden" name="per_page" value="<?php echo Request::get('per_page') ?>"/>

        <form action="" method="post" class="action_form">
            <div class="row">
                <div class="col-lg-4"></div>

                <div class="col-lg-4"></div>

                <div class="col-lg-4">
                    <form action="" method="get" class="search_form">
                        <div class="input-group">
                            <input name="q" value="<?php echo Request::get("q"); ?>" type="text" class=" form-control"
                                   placeholder="<?php echo trans("roles::roles.search_roles") ?> ...">
                            <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                        </span>
                        </div>
                    </form>
                </div>
            </div>
        </form>

        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5> <?php echo trans("roles::roles.roles") ?> </h5>
            </div>
            <div class="ibox-content">

                <?php if (count($roles)) { ?>

                <div class="row">

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 action-box">
                        <select name="action" class="form-control pull-left">
                            <option value="-1"
                                    selected="selected"><?php echo trans("roles::roles.bulk_actions"); ?></option>
                            <option value="delete"><?php echo trans("roles::roles.delete"); ?></option>
                        </select>
                        <button type="submit"
                                class="btn btn-primary pull-right"><?php echo trans("roles::roles.apply"); ?></button>
                    </div>

                    <div class="col-lg-6 col-md-4 hidden-sm hidden-xs"></div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">

                        <select class="form-control chosen-select chosen-rtl per_page_filter">
                            <option value="" selected="selected">
                                -- <?php echo trans("roles::roles.per_page"); ?> --
                            </option>
                            <?php foreach (array(10, 20, 30, 40) as $num) { ?>
                            <option
                                value="<?php echo $num; ?>"
                                <?php if ($num == $per_page) { ?> selected="selected" <?php } ?>><?php echo $num; ?></option>
                            <?php } ?>
                        </select>

                    </div>
                </div>
                <div class="table-responsive">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-hover"
                           id="jq-datatables-example">
                        <thead>
                        <tr>
                            <th style="width:35px"><input type="checkbox" class="check_all i-checks" name="ids[]"/>
                            </th>
                            <th><?php echo trans("roles::roles.name"); ?></th>
                            <th><?php echo trans("roles::roles.actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($roles as $role) { ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="i-checks" name="id[]"
                                       value="<?php echo $role->id; ?>"/>
                            </td>
                            <td>
                                <a class="text-navy"
                                   href="<?php echo URL::to(ADMIN) ?>/roles/<?php echo $role->id; ?>/edit">
                                    <strong> <?php echo $role->name; ?></strong>
                                </a>
                            </td>
                            <td class="center">
                                <a href="<?php echo URL::to(ADMIN) ?>/roles/<?php echo $role->id; ?>/edit">
                                    <i class="fa fa-pencil text-navy"></i>
                                </a>
                                <a class="ask" message="<?php echo trans("roles::roles.sure_delete"); ?>"
                                   href="<?php echo URL::to(ADMIN) ?>/roles/delete?id[]=<?php echo $role->id; ?>">
                                    <i class="fa fa-times text-navy"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>

                        </tbody>
                    </table>

                </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <?php echo trans("roles::roles.page"); ?>
                        <?php echo $roles->currentPage() ?>
                        <?php echo trans("roles::roles.of") ?>
                        <?php echo $roles->lastPage() ?>
                    </div>
                    <div class="col-lg-12 text-center">
                        <?php $roles->appends(Request::all())->render(); ?>
                    </div>

                </div>
                <?php } else { ?>
                <?php echo trans("roles::roles.no_records"); ?>
            <?php } ?>
            </div>
        </div>

    </div>

@stop

@push("footer")

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
                location.href = "<?php echo route("admin.roles.show") ?>?per_page=" + per_page;
            });

        });

    </script>
@endpush

