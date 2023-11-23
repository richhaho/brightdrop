
<div class="modal fade" id="modal-change-{{$id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation to change</h4>
      </div>
      <div class="modal-body">
          <div class="modal-body">
                <p>Are you sure you want to change to "{{$from=='Veem' ? 'Western Union':'Veem'}}"?</p>
          </div>
          <div class="modal-footer">
                {!! Form::open(['route' => 'admin.payroll.change','autocomplete' => 'off']) !!}
                <input type="hidden" name="id" value="{{$id}}">
                <input type="hidden" name="from" value="{{$from}}">
                <button type="submit" class="btn btn-success">&nbsp;&nbsp; Update</button>&nbsp;&nbsp;
                <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                {!! Form::close() !!}
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
  </div>
</div>
