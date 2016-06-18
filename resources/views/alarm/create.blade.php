{!! Form::open(array('url' => 'alarm', 'method' => 'POST', 'class' => 'form')) !!}

<div class="form-group">
  {!! Form::label('USER') !!}
  {!! Form::text('user_id', null,
      array('required',
      'class'=>'form-control')) !!}
</div>

<div class="form-group">
  {!! Form::label('Stop') !!}
  {!! Form::text('stop_id', null,
      array('required',
      'class'=>'form-control')) !!}
</div>

<div class="form-group">
  {!! Form::label('Route') !!}
  {!! Form::password('route_id', null,
      array('required',
      'class'=>'form-control')) !!}
</div>

<div class="form-group">
  {!! Form::label('Bus Num') !!}
  {!! Form::text('short_name', null,
      array('required',
      'class'=>'form-control')) !!}
</div>

<div class="form-group">
  {!! Form::label('on_off') !!}
  {!! Form::text('on_off', null,
      array('required',
      'class'=>'form-control')) !!}
</div>

{!! Form::submit('add Favorite') !!}

{!! Form::close() !!}