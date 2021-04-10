<div class="row">
    <div style=";" class="col-sm-7 mb10 mt10">
        <span style="display: inline-block;float: left; margin-right: 5px; line-height: 33px; font-weight: bold;">Bulk Action:</span>
<!--         <a style="margin-left: 5px" class="action_user_btn pull-left btn btn-primary btn-sm  action_user_btn mr5"
           data-action_type="bulk" data-status="enable"
           id="enable_user_btn" disabled="disabled"><i class="fa fa-check"></i> Activate</a>
        <a style="margin-left: 5px" class="action_user_btn pull-left btn btn-warning btn-sm action_user_btn mr5"
           data-action_type="bulk" data-action="disable"
           id="diable_user_btn" disabled="disabled"><i class="fa fa-ban"></i> Suspend</a>
 -->        <a style="margin-left: 5px" class="pull-left btn btn-danger btn-sm action_user_btn mr5"
           data-action_type="bulk" data-action="delete"
           id="delete_user_btn" disabled="disabled"><i class="fa fa-trash"></i> Delete</a>

        <div class="pull-left" style="border-left: 1px solid #e4eaec;margin-left: 15px;padding-left: 10px;">
            <input class="hide" id="fileupload" type="file" name="files[]"
                   data-url="{!!url('admin/ajax_upload_csv_users')!!} "/>
            <!-- <a style="margin-left: 5px" class=" btn btn-success btn-sm action_user_btn mr5" -->
               <!-- id="change_csvfile_button" href="javascript:void(0)"><i class="fa fa-upload"></i> Import -->
                <!-- Users</a> -->
            <a style="margin-left: 5px" class=" btn btn-success btn-sm action_user_btn mr5"
                href="/admin/users/create/"><i class="fa fa-upload"></i> Create User</a>
        </div>
    </div>
</div>
<div class="clearfix margin-bottom-15"></div>
<div class="row">
    <div class="col-sm-2">
        <form method="get">
            <div class="form-group">
                <div class="input-group">
                    <input type="number" class="form-control" name="limit"
                           value="{{ session('l5cp-user-limit') }}">

                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default">Limit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-6 pull-right">
        <form method="get">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-search"></i></div>
                    <input name="q" type="search" class="form-control" placeholder="Search"
                           value="{{ @$request->q }}">
                </div>
        </form>
    </div>
</div>
<div class="clearboth" style="height:10px;"></div>
</div>
<div class="row">
    <div class="col-md-12">

<!-- Striped rows table with hovers -->
<div class="table-responsive dataTables_wrapper">
    <form action="admin/ajax_edit_user" id="actions_user_form" class="te-ajax-form">
        <input type="hidden" name="user_action" id="user_action" value=""/>
        <table id="users_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th align="center" style="text-align:center;"><input type="checkbox" name="select_all" id="select_all"/>
                </th>
                <th>
                    {{ trans('ID') }}
                    <a href="?sort=id&order={{ @$request->order == 'desc' ? 'asc' : 'desc' }}"
                       class="disabled">
                        <i class="fa fa-sort{{ @$request->sort == 'id' ? (@$request->order == 'desc' ? '-alpha-asc' : '-alpha-desc') : '' }} pull-right"></i>
                    </a>
                </th>
                <th>
                    {{ trans('name') }}
                    <a href="?sort=name&order={{ @$request->order == 'desc' ? 'asc' : 'desc' }}"
                       class="disabled">
                        <i class="fa fa-sort{{ @$request->sort == 'name' ? (@$request->order == 'desc' ? '-alpha-asc' : '-alpha-desc') : '' }} pull-right"></i>
                    </a>
                </th>
                <th class="hidden-xs">
                    {{ trans('email') }}
                    <a href="?sort=email&order={{ @$request->order == 'desc' ? 'asc' : 'desc' }}"
                       class="disabled">
                        <i class="fa fa-sort{{ @$request->sort == 'email' ? (@$request->order == 'desc' ? '-alpha-asc' : '-alpha-desc') : '' }} pull-right"></i>
                    </a>
                </th>
                <th class="hidden-xs">
                    Status
                    <a href="?sort=status&order={{ @$request->order == 'desc' ? 'asc' : 'desc' }}"
                       class="disabled">
                        <i class="fa fa-sort{{ @$request->sort == 'status' ? (@$request->order == 'desc' ? '-alpha-asc' : '-alpha-desc') : '' }} pull-right"></i>
                    </a>
                </th>
                <th class="hidden-xs">
                    Last Updated
                    <a href="?sort=updated_at&order={{ @$request->order == 'desc' ? 'asc' : 'desc' }}"
                       class="disabled">
                        <i class="fa fa-sort{{ @$request->sort == 'updated_at' ? (@$request->order == 'desc' ? '-alpha-asc' : '-alpha-desc') : '' }} pull-right"></i>
                    </a>
                </th>
                <th class="hidden-xs">
                    Created At
                    <a href="?sort=created_at&order={{ @$request->order == 'desc' ? 'asc' : 'desc' }}"
                       class="disabled">
                        <i class="fa fa-sort{{ @$request->sort == 'created_at' ? (@$request->order == 'desc' ? '-alpha-asc' : '-alpha-desc') : '' }} pull-right"></i>
                    </a>
                </th>
                <th class="col-md-2 col-sm-3 col-xs-5">{{ trans('control') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td align="center">
                        <input class="select_user" type="checkbox" id="selected_user-{{$user->id}}"
                               name="selected_users[]" value="{{$user->id}}"/>
                    </td>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td class="hidden-xs">{{ $user->email }}</td>
                    <td class="hidden-xs">{{ $user->status }}</td>
                    <td class="hidden-xs">{{ $user->updated_at->diffForHumans() }}</td>
                    <td class="hidden-xs">{{ $user->created_at->format('d-M-Y') }}</td>
                    <td>
                        <div class="btn-group">
                            @if ($user->status == 'active')
                                <!-- <button class="btn btn-warning action_user_btn" -->
                                        <!-- data-user_id="{{$user->id}}" data-action_type="single" -->
                                        <!-- data-action="disable" type="button" title="Suspend"><span -->
                                            <!-- class="fa fa-ban"></span> -->
                                <!-- </button> -->
                            @else
                                <!-- <button class="btn btn-primary action_user_btn" -->
                                        <!-- data-user_id="{{$user->id}}" data-action_type="single" -->
                                        <!-- data-action="enable" type="button" title="Activate"><span -->
                                            <!-- class="fa fa-check"></span> -->
                                <!-- </button> -->
                            @endif
                            <button class="btn btn-danger action_user_btn"
                                    data-user_id="{{$user->id}}" data-action_type="single"
                                    data-action="delete" type="button" title="Delete"><span
                                        class="fa fa-trash"></span>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </form>
    <div id="users_table_pagination">
        {!! $users->render() !!}
    </div>
<!-- /Striped rows table with hovers -->
    </div>
</div>

