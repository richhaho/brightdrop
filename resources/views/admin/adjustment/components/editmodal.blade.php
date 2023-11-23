
<div class="modal fade" id="modal-edit-{{$id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Recurring Adjustment</h4>
      </div>
      {!! Form::open(['route' => 'admin.adjustment.update_recurring','autocomplete' => 'off']) !!}
      <div class="modal-body row" style="text-align: left;">
          <input type="hidden" name="id" value="{{$id}}">
          <div class="col-md-6 form-group"  >
              <label>Description:</label><br>
              <input type="text" class="form-control description" name="description" required value="{{$description}}" style="width: 100%">
          </div>
          <div class="col-md-6 form-group"  >
              <label>Amount:</label><br>
              <input type="number" min="0" class="form-control amount" name="amount" required value="{{$amount}}" style="width: 100%">
          </div>
          <?php
          $currency_type_list=[
            'php'=>'PHP',
            'mxn'=>'MXN',
            'usd'=>'USD',
        ];
          ?>
          <div class="col-md-6 form-group"  >
              <label>Currency Type:</label><br>
              {!! Form::select('currency_type',$currency_type_list,$currency_type, ['class' => 'form-control','style'=>'width:100%']) !!}
          </div>
          <div class="col-md-6 form-group">
              <label>Internal Notes:</label><br>
              <input type="text" class="form-control internal_note" name="internal_notes" value="{{$internal_notes}}" style="width: 100%">
          </div>

      </div>
      <div class="modal-footer">
            <input type="hidden" name="from" value="{{$from}}">
            <button type="submit" class="btn btn-success"> Update</button>&nbsp;&nbsp;
            <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
      </div>
      {!! Form::close() !!}
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
