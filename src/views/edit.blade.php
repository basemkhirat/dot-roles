@extends("admin::layouts.master")

@section("content")

    <form action="" method="post">

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <h2>
                    <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                    <?php echo trans("roles::roles.edit") ?>
                </h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo route("admin"); ?>"><?php echo trans("admin::common.admin") ?></a>
                    </li>
                    <li>
                        <a href="<?php echo route("admin.roles.show"); ?>"><?php echo trans("roles::roles.roles") ?></a>
                    </li>
                    <li class="active">
                        <strong><?php echo trans("roles::roles.edit") ?></strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">

                <?php if ($role) { ?>
                <a href="<?php echo route("admin.roles.create"); ?>" class="btn btn-primary btn-labeled btn-main">
                    <span class="btn-label icon fa fa-plus"></span> <?php echo trans("roles::roles.add_new") ?>
                </a>
                <?php } ?>

                <button type="submit" class="btn btn-flat btn-danger btn-main">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    <?php echo trans("roles::roles.save") ?>
                </button>

            </div>
        </div>

        <div class="wrapper wrapper-content fadeInRight">

            @include("admin::partials.messages")

            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">

                        <div class="panel-body">

                            <div class="form-group">
                                <input name="name" value="<?php echo @Request::old("name", $role->name); ?>"
                                       class="form-control input-lg" value=""
                                       placeholder="<?php echo trans("roles::roles.name"); ?>"/>
                            </div>

                            <?php foreach ($plugins as $plugin) { ?>

                            <?php if (count($plugin->permissions)) { ?>

                            <div class="panel panel-default ">

                                <div class="panel-heading">
                                    <a class="accordion-toggle text-navy" data-toggle="collapse"
                                       href="#collapse-<?php echo $plugin->key; ?>">
                                        <strong><?php echo ucfirst($plugin->name); ?></strong>
                                    </a>
                                </div>

                                <div id="collapse-<?php echo $plugin->key; ?>" class="panel-collapse in">
                                    <div class="panel-body">
                                        <?php foreach ($plugin->permissions as $slug) { ?>
                                        <label class="checkbox">
                                            <input
                                                <?php if ($role and in_array($plugin->key . "." . $slug, $role_permissions)) { ?> checked="checked"
                                                <?php } ?>
                                                type="checkbox" name="permissions[]"
                                                value="<?php echo $plugin->key . "." . $slug; ?>"
                                                class="switcher permission-switcher switcher-sm">
                                            <span style="margin: 0 10px 10px;">
                                                <?php echo ucfirst(trans($plugin->key . "::" . $plugin->key . ".permissions." . $slug)); ?>
                                            </span>
                                        </label>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <?php } ?>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

@stop

@push("footer")

    <script>
        $(document).ready(function () {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.permission-switcher'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html);
            });
        });
    </script>

@endpush
