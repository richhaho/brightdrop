<a href="#" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-edit-holiday-{{$id}}"><i class="fa fa-edit"></i></a>

 
<div class="modal fade" id="modal-edit-holiday-{{$id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Holiday</h4>
      </div>
      {!! Form::open(['route' => 'admin.updateholiday','autocomplete' => 'off']) !!}
      <input type="hidden" name="holiday_id" value="{{$id}}">
      <div class="modal-body">
          <table id="detail_table" class="table table-hover text-center table-bordered">
            <thead>
            <tr class="warning">
                <th width="50%">Holiday Name</th>
                <th width="50%">Holiday Date</th>
                 
            </tr>
            </thead>
            <tbody class=" add_advance">
            <tr>
                <td><input name="holiday_name" type="text" class="form-control" value="{{$holiday_name}}" required></td>
                <td><input name="holiday_date" type="date" class="form-control" value="{{$holiday_date}}" required></td>
            </tr>
            </tbody>
            </table>
      </div>
      <div class="modal-footer">
            <button type="submit" class="btn btn-success"> Submit</button>&nbsp;&nbsp;
            <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
      </div>
      {!! Form::close() !!}
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
